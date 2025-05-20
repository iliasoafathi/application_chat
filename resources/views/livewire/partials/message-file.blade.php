@props(['message', 'isSender' => false])

@php
    $isImage = str_starts_with($message->file_type, 'image/');
    $fileUrl = asset('storage/' . $message->file_path);
    $bgColor = $isSender ? 'bg-indigo-100' : 'bg-gray-100';
    $textColor = $isSender ? 'text-indigo-800' : 'text-gray-800';
@endphp

<div class="{{ $bgColor }} rounded-lg p-3 inline-block max-w-xs">
    @if($isImage)
        <a href="{{ $fileUrl }}" target="_blank" class="block">
            <img src="{{ $fileUrl }}" alt="Attached image" class="rounded max-h-48 w-auto">
        </a>
    @else
        <a href="{{ $fileUrl }}" download="{{ $message->file_name_original }}" 
           class="flex items-center space-x-2 {{ $textColor }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <div class="truncate max-w-[180px]">
                <p class="text-sm font-medium truncate">{{ $message->file_name_original }}</p>
                <p class="text-xs text-gray-500">{{ round(filesize(storage_path('app/public/' . $message->file_path)) / 1024) }} KB</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
        </a>
    @endif
</div>