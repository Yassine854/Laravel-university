<x-app-layout>
    <x-slot name="header">
        <div class="text-center">{{ $new->title }}</div>
    </x-slot>

    <x-jet-bar-container>
        <div class="list-group">


                <div class="p-3 d-flex w-100 justify-content-between bg-light rounded">
                    <h5 class="mb-1">{{ $new->description }}</h5>
                    <small class="text-muted">{{ $new->created_at }}</small>
                </div>

        </div>
    </x-jet-bar-container>
</x-app-layout>
