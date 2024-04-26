<x-app-layout>
    <x-slot name="header">
        Semestre {{ $semester }} <br> <br>
        Notes
    </x-slot>

    <x-jet-bar-container class="mt-8">
        <div class="table-responsive">
            <table class="table table-bordered table-striped bg-white">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th>Matiére</th>
                        <th>Note examen</th>
                        <th>Note DS</th>
                        <th>Note TP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marks as $mark)
                    <tr>
                        <td>{{ $mark->subject->name }}</td>
                        <td>{{ $mark->note_exam }}</td>
                        <td>{{ $mark->note_ds }}</td>
                        <td>{{ $mark->note_tp }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-jet-bar-container>
</x-app-layout>
