<x-app-layout>
    <x-slot name="header">
        Emploi du temps
    </x-slot>

    <x-jet-bar-container class="flex flex-col items-center justify-center mt-8">
        @if ($timetable)
            <div class="w-full h-screen flex items-center justify-center">
                <embed src="{{ asset('storage/timetables/' . $timetable->file) }}"
                       type="application/pdf"
                       width="80%"
                       height="80%"
                       class="shadow-lg"
                       frameborder="0">
            </div>
        @else
            <p class="text-center">Aucun emploi du temps n'est disponible pour le moment.</p>
        @endif
    </x-jet-bar-container>
</x-app-layout>
