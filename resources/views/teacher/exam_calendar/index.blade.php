<x-app-layout>
    <x-slot name="header">
        Calendrier d'examens
    </x-slot>

    <x-jet-bar-container class="flex flex-col items-center justify-center mt-8">
        @if ($latestCalendar)
            <div class="w-full h-screen flex items-center justify-center">
                <embed src="{{ asset('storage/calendars/' . $latestCalendar->file) }}"
                       type="application/pdf"
                       width="80%"
                       height="80%"
                       class="shadow-lg"
                       frameborder="0">
            </div>
        @else
            <p class="text-center">Aucun calendrier d'examens n'est disponible pour le moment.</p>
        @endif
    </x-jet-bar-container>
</x-app-layout>
