<x-app-layout>


    <x-slot name="header">
           Département: {{ $departmentName}} <br> <br>
           Filiére: {{ $fieldName }} <br> <br>
           Emplois du temps des étudiants
    </x-slot>

    <x-jet-bar-container>
{{-- create timetable --}}

        <div
            x-data="{ openModal: {{ $errors->any() && session('form_type') === 'create' ? 'true' : 'false' }}, selectedRole: '' }">
            <!-- Button to open modal -->
            <button id="openModalButton" @click="openModal = true"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="float: right">
                <x-jet-bar-icon type="plus" fill />
            </button>
            <br><br>

            <!-- Modal -->
            <div x-show="openModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
                <div class="d-flex align-items-center justify-content-center min-vh-100 px-4 pt-4 pb-5 text-center">
                    <div class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>

                    <!-- This element is to trick the browser into centering the modal contents. -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <!-- Modal content -->
                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <!-- Modal Header -->
                        <div class="bg-gray-100 px-4 py-3 sm:px-6">
                            <h3 class="text-lg font-medium text-gray-900">Ajouter Emploi du temps</h3>
                        </div>

                        <!-- Modal Body -->
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <!-- Your form content goes here -->
                            <form action="{{ route('admin.timetables.createStudentsTimetable') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li class="text-red-700">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <br>
                                </div>
                                @endif


                                <div class="mb-4">
                                    <label for="group" class="block text-sm font-medium text-gray-700">Groupe</label>
                                    <select name="group" id="groupe_id"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                        <option value="">Tous les groupes</option> <!-- Option for showing all groups -->

                                        <!-- Iterate through group numbers from 1 to 4 -->
                                        @for ($i = 1; $i <= 4; $i++)
                                            <!-- Check if the group number exists in $timetables -->
                                            @if (!$timetables->contains('group', $i))
                                                <!-- If the group number does not exist, show it as an option -->
                                                <option value="{{ $i }}">Groupe {{ $i }}</option>
                                            @endif
                                        @endfor
                                    </select>
                                </div>


                                <!-- File upload input -->
                                <div class="mb-4">
                                    <label for="file" class="block text-sm font-medium text-gray-700">Emploi du temps (PDF)</label>
                                    <input class="form-control-file" id="file" type="file" name="file">

                                </div>
                                <input type="hidden" name="department_id" value="{{ $department }}">
                                <input type="hidden" name="field_id" value="{{ $field }}">


                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Ajouter</button>
                                    <button type="button" @click="openModal = false"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div>

            @if(session('success'))
            <x-jet-bar-alert type="success" text="Emploi du temps ajouté avec succès !" />

            @endif

            @if(session('warning'))
            <x-jet-bar-alert type="warning" text="Emploi du temps modifié avec succès !" />

            @endif


            @if(session('danger'))
            <x-jet-bar-alert type="danger" text="Emploi du temps supprimé !" />

            @endif




            <x-jet-bar-table :headers="['ID','Groupe','Created_at','', '','']" class="flex justify-center">
                <template x-data="{ total:1 }"  x-for="index in total">
                    @foreach($timetables as $timetable)
                    <tr class="hover:bg-gray-50">
                        <x-jet-bar-table-data>
                            {{ $timetable->id }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                          Groupe {{ $timetable->group }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            {{ $timetable->created_at }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data >

                            <!-- Add a link to download the timetable -->

                            <a href="{{ route('admin.timetables.downloadTimetable', ['fileName' => $timetable->file]) }}" target="_blank" class="inline-block bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Télécharger</a>



                        </x-jet-bar-table-data>


                        <x-jet-bar-table-data>
                            <a href="#" class="text-gray-400 hover:text-gray-500">

{{-- edit timetable --}}
<div x-data="{ editModal: {{ $errors->any() && session('edit_timetable_id') == $timetable->id && session('form_type') === 'edit' ? 'true' : 'false' }}, edittimetableId: {{ $timetable->id }} }">
    <!-- Button to open modal -->
    <button @click="editModal = true" class="px-4 py-1 text-sm text-blue-600 bg-blue-200 rounded-full">
        <x-jet-bar-icon type="pencil" fill />
    </button>

    <!-- Modal -->
    <div x-show="editModal" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="d-flex align-items-center justify-content-center min-vh-100 px-4 pt-4 pb-5 text-center">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="bg-gray-100 px-4 py-3 sm:px-6">
                    <h3 class="text-lg font-medium text-gray-900">Modifier Emploi du temps</h3>
                </div>

                <!-- Modal Body -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Your form content goes here -->
                    <form action="{{ route('admin.timetables.updateStudentsTimetable', ['department' => $department,'field' => $field,'id'=>$timetable->id]) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li class="text-red-700">{{ $error }}</li>
                                @endforeach
                            </ul>
                            <br>
                        </div>
                        @endif

                        <div class="mb-4">
                            <label for="group" class="block text-sm font-medium text-gray-700">Groupe</label>
                            <select name="group" id="group" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                <!-- Loop through options -->
                                <option value="{{ $timetable->group}}">Groupe {{ $timetable->group }}</option>

                                @for ($i = 1; $i <= 4; $i++)
                                    <!-- Exclude the current value from being repeated -->
                                    @if ($i != $timetable->group)
                                        <option value="{{ $i }}">Groupe {{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="fileEdit" class="block text-sm font-medium text-gray-700">Emploi du temps (PDF)</label>
                            <div class="input-group">
                                <input id="oldFileName" type="text" name="oldFileName" value="{{ $timetable->file ? $timetable->file : 'No file selected' }}" class="form-control" readonly>
                                <label class="input-group-text btn btn-outline-primary" for="fileEdit">Sélectionner fichier</label>
                                <input id="fileEdit" type="file" name="fileEdit" class="form-control hidden" onchange="updateFileName(this)">
                            </div>
                        </div>

                        <!-- File upload input -->
                        <input type="hidden" name="department_id" value="{{ $department }}">
                        <input type="hidden" name="field_id" value="{{ $field }}">

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Modifier</button>
                            <button type="button" @click="editModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annuler</button>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
            </div>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        var fileName = input.files[0].name;
        document.getElementById('oldFileName').value = fileName;
    }
</script>




                            </a>
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <div x-data="{ deleteModal: false, timetableId: {{ $timetable->id }} }">
                                    <button @click="deleteModal = true" class="px-4 py-1 text-sm text-red-400 bg-red-200 rounded-full">
                                        <x-jet-bar-icon type="trash" fill />
                                    </button>
                                    <!-- Delete Modal -->
                                    <div x-show="deleteModal" class="fixed z-10 inset-0 overflow-y-auto">
                                        <div class="d-flex align-items-center justify-content-center min-vh-100 px-4 pt-4 pb-5 text-center">
                                            <div class="fixed inset-0 transition-opacity">
                                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                            </div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                <div class="bg-gray-100 px-4 py-3 sm:px-6">
                                                    <h3 class="text-lg font-medium text-gray-900">Confirmer suppression</h3>
                                                </div>
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <form :action="`{{ route('admin.timetables.destroyStudentsTimetable', ['department' => $department,'field' => $field,'id'=>$timetable->id]) }}`" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <p class="text-sm text-gray-500">Etes-vous sûr que vous voulez supprimer cet emploi du temps?</p>
                                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                                            <button type="submit"
                                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                            Oui
                                                          </button>
                                                            <button type="button" @click="deleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annuler</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </x-jet-bar-table-data>

                    </tr>
                    @endforeach
                </template>
            </x-jet-bar-table>




        </div>

        <script>
            setTimeout(function() {
        document.getElementById('alert').style.display = 'none';
    }, 5000);
    function hideAlert() {
        document.getElementById('alert').style.display = 'none';
    }
    document.getElementById('file').addEventListener('change', function(event) {
        var fileName = event.target.files[0].name;
        document.getElementById('oldFileName').value = fileName;
    });

    function clearFile() {
        document.getElementById('file').value = '';
        document.getElementById('oldFileName').value = 'No file selected';
    }
        </script>


    </x-jet-bar-container>
</x-app-layout>
