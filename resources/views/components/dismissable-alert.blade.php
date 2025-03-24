@props([
    'type' => 'info',
    'message' => 'This is an alert message',
    'id' => 'custom-alert',
])

<div class="alert alert-{{ $type }} hidden cursor-pointer my-2 custom-alert relative p-4"
    id="{{ $id }}">

    <div class="flex justify-between items-center absolute inset-0 p-4">
        <div>
            <p class="text-xs alert-message-content" data-alert-id="{{ $id }}">
                {{ $message }}
            </p>
        </div>
        <div class="dismiss-error">
            <x-lucide-x class="h-4 w-4 " data-alert-id="{{ $id }}" />
        </div>
    </div>

</div>
