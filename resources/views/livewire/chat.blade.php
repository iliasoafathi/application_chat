<div>
    <!-- Header avec barre de recherche -->
    <div class="bg-white py-3 border-b">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Nom de l'utilisateur -->
                <div class="flex items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $user->name }}
                    </h2>
                    <!-- Indicateur de connexion -->
                    <span class="ml-2 w-2 h-2 rounded-full bg-green-500"></span>
                </div>

                <!-- Barre de recherche -->
                <div class="relative w-1/3">
                    <input type="text" wire:model.live="search" placeholder="Search messages..."
                        class="pl-10 pr-16 py-2 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200">

                    <!-- Icône de recherche -->
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>

                    <!-- Boutons de navigation dans les résultats -->
                    @if($search)
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex items-center space-x-2">
                            <!-- Bouton effacer -->
                            <button wire:click="resetSearch" class="text-gray-400 hover:text-gray-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            
                            <!-- Compteur de résultats -->
                            @if(count($matchedIndexes) > 0)
                                <span class="text-sm text-gray-500">
                                    {{ $currentMatch + 1 }}/{{ count($matchedIndexes) }}
                                </span>
                                
                                <!-- Boutons précédent/suivant -->
                                <button wire:click="prevMatch" class="text-gray-400 hover:text-indigo-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button wire:click="nextMatch" class="text-gray-400 hover:text-indigo-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Zone de conversation -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="chat-container" 
                 class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 overflow-y-auto h-[calc(100vh-12rem)] scroll-smooth"
                 x-data="{ isTyping: false }"
                 x-on:typing-start.window="isTyping = true"
                 x-on:typing-stop.window="setTimeout(() => isTyping = false, 2000)">

                <!-- Liste des messages -->
                <div class="w-full px-5 py-8 space-y-4" id="message-list">
                    @forelse ($messages as $index => $message)
                        <div id="message-{{ $index }}" 
                             wire:key="message-{{ $message->id }}"
                             class="@if(in_array($index, $matchedIndexes) && $search) bg-yellow-50 @endif 
                                    @if($message->sender_id == auth()->id()) justify-end @endif
                                    flex transition-all duration-200">

                            <!-- Message de l'expéditeur -->
                            @if($message->sender_id != auth()->id())
                                <div class="flex gap-3 max-w-[80%]">
                                    <!-- Avatar -->
                                    <img src="{{ $message->sender->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($message->sender->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                         alt="{{ $message->sender->name }}"
                                         class="w-10 h-10 rounded-full object-cover">

                                    <!-- Contenu du message -->
                                    <div>
                                        <!-- Nom et date -->
                                        <div class="flex items-baseline gap-2 mb-1">
                                            <span class="font-semibold text-gray-800">{{ $message->sender->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $message->created_at->format('h:i A') }}</span>
                                        </div>

                                        <!-- Texte ou fichier -->
                                        @if($message->message)
                                            <div class="px-4 py-2 bg-gray-100 rounded-lg rounded-tl-none">
                                                <p class="text-gray-800">
                                                    {!! $search ? $this->highlightText($message->message, $search) : nl2br(e($message->message)) !!}
                                                </p>
                                            </div>
                                        @else
                                            <div class="mt-1">
                                                @include('livewire.partials.message-file', ['message' => $message])
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            
                            <!-- Message du destinataire (utilisateur actuel) -->
                            @else
                                <div class="flex gap-3 max-w-[80%]">
                                    <!-- Contenu du message -->
                                    <div class="text-right">
                                        <!-- Date -->
                                        <div class="mb-1">
                                            <span class="text-xs text-gray-500">{{ $message->created_at->format('h:i A') }}</span>
                                        </div>

                                        <!-- Texte ou fichier -->
                                        @if($message->message)
                                            <div class="px-4 py-2 bg-indigo-600 text-white rounded-lg rounded-tr-none">
                                                <p>{!! $search ? $this->highlightText($message->message, $search) : nl2br(e($message->message)) !!}</p>
                                            </div>
                                        @else
                                            <div class="mt-1">
                                                @include('livewire.partials.message-file', ['message' => $message, 'isSender' => true])
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Avatar -->
                                    <img src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                         alt="{{ auth()->user()->name }}"
                                         class="w-10 h-10 rounded-full object-cover">
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-10">
                            No messages yet. Start the conversation!
                        </div>
                    @endforelse

                    <!-- Indicateur "typing" -->
                    <div x-show="isTyping" x-transition class="flex gap-3">
                        <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                             alt="{{ $user->name }}"
                             class="w-10 h-10 rounded-full object-cover">
                        <div class="px-4 py-2 bg-gray-100 rounded-lg rounded-tl-none">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce"></div>
                                <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0.2s"></div>
                                <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0.4s"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Zone de saisie -->
                <div class="sticky bottom-0 bg-white border-t border-gray-200 px-4 py-3">
                    <!-- Prévisualisation du fichier -->
                    @if($file)
                        <div class="flex items-center justify-between mb-2 p-2 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-2">
                                @if(str_starts_with($file->getMimeType(), 'image/'))
                                    <img src="{{ $file->temporaryUrl() }}" 
                                         alt="Preview" 
                                         class="h-12 w-12 object-cover rounded">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                @endif
                                <span class="text-sm font-medium truncate max-w-xs">
                                    {{ $file->getClientOriginalName() }}
                                </span>
                            </div>
                            <button wire:click="$set('file', null)" class="text-gray-400 hover:text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    <!-- Formulaire -->
                    <form wire:submit.prevent="sendMessage" class="flex items-center space-x-2">
                        <!-- Bouton pièce jointe -->
                        <label class="cursor-pointer text-gray-400 hover:text-indigo-600 transition p-2 rounded-full hover:bg-gray-100">
                            <input type="file" wire:model="file" class="hidden" wire:change="sendFileMessage">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </label>

                        <!-- Champ de texte -->
                        <input type="text" 
                               wire:model="message" 
                               wire:keydown.debounce.500ms="userTyping"
                               placeholder="Type your message..."
                               class="flex-1 border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                        <!-- Bouton d'envoi -->
                        <button type="submit" 
                                class="bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition disabled:opacity-50"
                                wire:loading.attr="disabled">
                            <svg wire:loading.remove wire:target="sendMessage" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <svg wire:loading wire:target="sendMessage" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
document.addEventListener('livewire:initialized', () => {
    const chatContainer = document.getElementById('chat-container');
    let typingTimer;

    // Fonction pour le scroll automatique
    function scrollToBottom() {
        if (chatContainer) {
            chatContainer.scrollTo({
                top: chatContainer.scrollHeight,
                behavior: 'smooth'
            });
        }
    }

    // Scroll initial
    scrollToBottom();

    // Écouteurs Livewire
    Livewire.on('messages-updated', () => {
        setTimeout(scrollToBottom, 100);
    });

    Livewire.on('scroll-to-match', (event) => {
        const element = document.getElementById(`message-${event.index}`);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            element.classList.add('bg-yellow-100', 'transition', 'duration-300');
            setTimeout(() => element.classList.remove('bg-yellow-100'), 2000);
        }
    });

    // Écouteurs d'événements Pusher
    window.Echo.private(`chat-channel.${Livewire.entangle('senderId')}`)
        .listen('UserTyping', (e) => {
            window.dispatchEvent(new CustomEvent('typing-start'));
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                window.dispatchEvent(new CustomEvent('typing-stop'));
            }, 2000);
        })
        .listen('MessageSentEvent', (e) => {
            if (!document.hasFocus()) {
                new Audio('{{ asset("sounds/notification.mp3") }}').play();
            }
        });

    // Auto-focus sur le champ de message
    document.getElementById('message-input')?.focus();
});
</script>
@endscript