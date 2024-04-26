<x-app-layout>
    <x-slot name="header">
        Liste de groupe <br> <br>
        {{ auth()->user()->field->name }} G0{{ auth()->user()->groupe }}
    </x-slot>

    <x-jet-bar-container class="mt-8">
        <div class="table-responsive">
            <table class="table table-bordered table-striped bg-white">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th>Ordre</th>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $key => $student)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $student->id }}</td>
                        <td>{{ strtoupper($student->last_name) }}</td>
                        <td>{{ strtoupper($student->name) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-jet-bar-container>
</x-app-layout>
