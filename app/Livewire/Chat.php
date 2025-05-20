<?php
namespace App\Livewire;

use App\Events\MessageSentEvent;
use App\Events\UnreadMessage;
use App\Events\UserTyping;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Chat extends Component
{
    use WithFileUploads;

    public $user;
    public $senderId;
    public $receiverId;
    public $message;
    public $messages = [];
    public $file;
    public $search = '';
    public $matchedIndexes = [];
    public $currentMatch = 0;

    public function mount($userId)
    {
        $this->senderId = Auth::user()->id;
        $this->receiverId = $userId;

        $this->user = User::find($userId);
        $this->messages = $this->getMessages();
        $this->markMessagesAsRead();

        $this->dispatch('chat-mounted');
    }

    public function render()
    {
        $this->markMessagesAsRead();
        return view('livewire.chat');
    }

    public function sendMessage()
    {
        if (!$this->message && !$this->file) {
            return;
        }

        $sentMessage = $this->saveMessage()->load('sender:id,name', 'receiver:id,name');
        $this->messages = $this->getMessages();

        broadcast(new MessageSentEvent($sentMessage))->toOthers();
        broadcast(new UnreadMessage($this->receiverId, $this->senderId, $this->getUnreadMessagesCount()))->toOthers();

        $this->reset(['message', 'file']);
        $this->dispatch('messages-updated');
    }

    #[On('echo-private:chat-channel.{senderId},MessageSentEvent')]
    public function listenMessage($event)
    {
        $this->messages = $this->getMessages();
        $this->dispatch('messages-updated');
    }

    public function saveMessage()
    {
        $filePath = $fileOriginalName = $fileName = $fileType = null;

        if ($this->file) {
            $fileOriginalName = $this->file->getClientOriginalName();
            $fileName = $this->file->hashName();
            $filePath = $this->file->store('chat_files', 'public');
            $fileType = $this->file->getMimeType();
        }

        return Message::create([
            'message' => $this->message,
            'sender_id' => $this->senderId,
            'receiver_id' => $this->receiverId,
            'file_name' => $fileName,
            'file_name_original' => $fileOriginalName,
            'file_path' => $filePath,
            'file_type' => $fileType,
        ]);
    }

    public function getMessages()
    {
        return Message::with('sender:id,name', 'receiver:id,name')
            ->where(function($query) {
                $query->where('sender_id', $this->senderId)
                    ->where('receiver_id', $this->receiverId);
            })
            ->orWhere(function($query) {
                $query->where('sender_id', $this->receiverId)
                    ->where('receiver_id', $this->senderId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function userTyping()
    {
        broadcast(new UserTyping($this->senderId, $this->receiverId))->toOthers();
    }

    public function getUnreadMessagesCount()
    {
        return Message::where('receiver_id', $this->receiverId)
            ->where('sender_id', $this->senderId)
            ->where('is_read', false)
            ->count();
    }

    public function markMessagesAsRead()
    {
        Message::where('receiver_id', $this->senderId)
            ->where('sender_id', $this->receiverId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        broadcast(new UnreadMessage($this->senderId, $this->receiverId, 0))->toOthers();
    }

    public function sendFileMessage()
    {
        if ($this->file) {
            $this->sendMessage();
        }
    }

    public function highlightText($text, $search)
    {
        $escaped = preg_quote($search, '/');
        return preg_replace('/('.$escaped.')/i', '<mark>$1</mark>', e($text));
    }

    public function updatedSearch()
    {
        $this->matchedIndexes = [];

        if (!empty($this->search)) {
            foreach ($this->messages as $index => $message) {
                if (stripos($message->message, $this->search) !== false) {
                    $this->matchedIndexes[] = $index;
                }
            }
        }

        $this->currentMatch = count($this->matchedIndexes) > 0 ? 0 : -1;
        $this->dispatch('search-updated');
    }

    public function nextMatch()
    {
        if (count($this->matchedIndexes)) {
            $this->currentMatch = ($this->currentMatch + 1) % count($this->matchedIndexes);
            $this->dispatch('scroll-to-match', index: $this->matchedIndexes[$this->currentMatch]);
        }
    }

    public function prevMatch()
    {
        if (count($this->matchedIndexes)) {
            $this->currentMatch = ($this->currentMatch - 1 + count($this->matchedIndexes)) % count($this->matchedIndexes);
            $this->dispatch('scroll-to-match', index: $this->matchedIndexes[$this->currentMatch]);
        }
    }

    public function resetSearch()
    {
        $this->reset(['search', 'matchedIndexes', 'currentMatch']);
        $this->dispatch('messages-updated');
    }
}