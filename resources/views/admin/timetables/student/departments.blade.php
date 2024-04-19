<x-app-layout>
    <x-slot name="header">
        Départements
    </x-slot>

    <x-jet-bar-container>




        <div>

            <x-jet-bar-table :headers="['ID', 'Nom']">
                @foreach($departments as $department)
                    <tr class="hover:bg-gray-50" style="cursor: pointer;" onclick="window.location='{{ route('admin.timetables.fields', ['department' => $department->id]) }}';">
                        <x-jet-bar-table-data>
                            <span class="mr-1">{{ $department->id }}</span> <!-- Department ID -->
                            <span class="arrow">&#8594;</span> <!-- Right arrow -->
                        </x-jet-bar-table-data>
                        <x-jet-bar-table-data>{{ $department->name }}</x-jet-bar-table-data>
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

<style>
    .arrow {
        font-size: 1.5rem; /* Adjust the size as needed */
        vertical-align: middle;
        margin-left: 5px; /* Adjust the spacing as needed */
    }
</style>

    </x-jet-bar-container>
</x-app-layout>
