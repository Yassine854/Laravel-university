<x-app-layout>


    <x-slot name="header">
        @foreach ($fields as $field )
           Département: {{ $field->department->name }} <br> <br>
           Liste des filiéres
            @break
        @endforeach
    </x-slot>

    <x-jet-bar-container>

        <div>

            @if(session('success'))
            <x-jet-bar-alert type="success" text="Filiére ajouté avec succès !" />

            @endif

            @if(session('warning'))
            <x-jet-bar-alert type="warning" text="Filiére modifié avec succès !" />

            @endif


            @if(session('danger'))
            <x-jet-bar-alert type="danger" text="Filiére supprimé !" />

            @endif




            <x-jet-bar-table :headers="['ID', 'Nom']">
                @foreach($fields as $field)
                    <tr class="hover:bg-gray-50" style="cursor: pointer;" onclick="window.location='{{ route('admin.timetables.StudentsTimetable', ['department' => $field->department->id,'field'=>$field->id]) }}';">
                        <x-jet-bar-table-data>
                            <span class="mr-1">{{ $field->id }}</span> <!-- Department ID -->
                            <span class="arrow">&#8594;</span> <!-- Right arrow -->
                        </x-jet-bar-table-data>
                        <x-jet-bar-table-data>{{ $field->name }}</x-jet-bar-table-data>
                    </tr>
                @endforeach
            </x-jet-bar-table>




        </div>

        <script>
            setTimeout(function() {
        document.getElementById('alert').style.display = 'none';
    }, 5000);
    function hideAlert() {
        document.getElementById('alert').style.display = 'none';
    }

        </script>


    </x-jet-bar-container>
</x-app-layout>
