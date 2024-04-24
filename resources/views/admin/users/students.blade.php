<x-app-layout>
    <x-slot name="header">
        Etudiants
    </x-slot>

    <x-jet-bar-container>

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
                            <h3 class="text-lg font-medium text-gray-900">Ajouter Etudiant</h3>
                        </div>

                        <!-- Modal Body -->
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <!-- Your form content goes here -->
                            <form action="{{ route('admin.users.createStudent') }}" method="POST">
                                @csrf
                                @method('POST')
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
                                    <label for="name" class="block text-sm font-medium text-gray-700">Prénom</label>
                                    <input type="text" name="name" id="name" autocomplete="name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                </div>

                                <div class="mb-4">
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">Nom</label>
                                    <input type="text" name="last_name" id="last_name" autocomplete="last_name"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                                    <input type="text" name="address" id="address" autocomplete="address"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                </div>

                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <input type="text" name="phone" id="phone" autocomplete="phone"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                </div>

                                <div class="mb-4">
                                    <label for="department_id" class="block text-sm font-medium text-gray-700">Département</label>
                                    <select name="department_id" id="department_id"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                        <option  value="">Tous les départements</option> <!-- Option for showing all departments -->
                                        @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4" id="fieldSelectWrapper" style="display: none;">
                                    <label for="field_id" class="block text-sm font-medium text-gray-700">Filière</label>
                                    <select name="field_id" id="field_id"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black" disabled>
                                        <option  value="">Toutes les filières</option> <!-- Option for showing all fields -->
                                        @foreach ($fields as $field)
                                        <option value="{{ $field->id }}" data-department="{{ $field->department_id }}">{{ $field->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4" id="groupeSelectWrapper" style="display: none;">
                                    <label for="groupe_id" class="block text-sm font-medium text-gray-700">Groupe</label>
                                    <select name="groupe" id="groupe_id"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black" disabled>
                                        <option value="">Tous les groupes</option> <!-- Option for showing all groups -->
                                        <option value="1">Groupe 1</option>
                                        <option value="2">Groupe 2</option>
                                        <option value="3">Groupe 3</option>
                                        <option value="4">Groupe 4</option>
                                    </select>
                                </div>


                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" autocomplete="email"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de
                                        passe</label>
                                    <input type="password" name="password" id="password" autocomplete="password"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                </div>

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
            <x-jet-bar-alert type="success" text="Etudiant ajouté avec succès !" />

            @endif

            @if(session('warning'))
            <x-jet-bar-alert type="warning" text="Etudiant modifié avec succès !" />

            @endif


            @if(session('danger'))
            <x-jet-bar-alert type="danger" text="Etudiant supprimé !" />

            @endif




            <x-jet-bar-table :headers="['ID', 'Nom', 'Prénom', 'Rôle' ,'Email','Created_at','', '']">
                <template x-data="{ total:1 }" x-for="index in total">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <x-jet-bar-table-data>
                            {{ $user->id }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            {{ $user->last_name }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            {{ $user->name }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            @if($user->role_id == 1)
                            <x-jet-bar-badge text="Admin" type="danger" />
                            @elseif($user->role_id == 3)
                            <x-jet-bar-badge text="Etudiant" type="warning" />
                            @elseif($user->role_id == 2)
                            <x-jet-bar-badge text="Etudiant" type="info" />
                            @endif
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            {{ $user->email }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            {{ $user->created_at }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            <a href="#" class="text-gray-400 hover:text-gray-500">


                                <div x-data="{ editModal: {{ $errors->any() &&  session('edit_user_id') == $user->id  &&  session('form_type') === 'edit'? 'true' : 'false' }}, editedRole: {{ $user->role_id }}, editUserId: {{ $user->id }} }">
                                    <!-- Button to open modal -->
                                    <button @click="editModal = true; updateFieldSelectEdit({{ json_encode($user) }})" class="px-4 py-1 text-sm text-blue-600 bg-blue-200 rounded-full">
                                        <x-jet-bar-icon type="pencil" fill />
                                    </button>


                                    <!-- Modal -->
                                    <div x-show="editModal" class="fixed z-10 inset-0 overflow-y-auto"
                                        style="display: none;">
                                        <div
                                            class="d-flex align-items-center justify-content-center min-vh-100 px-4 pt-4 pb-5 text-center">
                                            <div class="fixed inset-0 transition-opacity">
                                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                            </div>

                                            <!-- This element is to trick the browser into centering the modal contents. -->
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                aria-hidden="true">&#8203;</span>

                                            <!-- Modal content -->
                                            <div
                                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                <!-- Modal Header -->
                                                <div class="bg-gray-100 px-4 py-3 sm:px-6">
                                                    <h3 class="text-lg font-medium text-gray-900">Modifier Etudiant
                                                    </h3>
                                                </div>

                                                <!-- Modal Body -->
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <!-- Your form content goes here -->
                                                    <form
                                                        action="{{ route('admin.users.updateStudent', ['user' => $user->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
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
                                                            <label for="name"
                                                                class="block text-sm font-medium text-gray-700">Prénom</label>
                                                            <input type="text" name="name" id="name" autocomplete="name"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black"
                                                                value="{{ $user->name }}">
                                                        </div>

                                                        <div class="mb-4">
                                                            <label for="last_name"
                                                                class="block text-sm font-medium text-gray-700">Nom</label>
                                                            <input type="text" name="last_name" id="last_name"
                                                                autocomplete="last_name"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black"
                                                                value="{{ $user->last_name }}">
                                                        </div>

                                                        <div class="mb-4"
                                                            x-show="editedRole == '2' || editedRole == '3'">
                                                            <label for="address"
                                                                class="block text-sm font-medium text-gray-700">Adresse</label>
                                                            <input type="text" name="address" id="address"
                                                                autocomplete="address"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black"
                                                                value="{{ $user->address }}">
                                                        </div>

                                                        <div class="mb-4"
                                                            x-show="editedRole == '2' || editedRole == '3'">
                                                            <label for="phone"
                                                                class="block text-sm font-medium text-gray-700">Téléphone</label>
                                                            <input type="text" name="phone" id="phone"
                                                                autocomplete="phone"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black"
                                                                value="{{ $user->phone }}">
                                                        </div>


                                                        <div class="mb-4">
                                                            <label for="department_idEdit{{ $user->id }}" class="block text-sm font-medium text-gray-700">Département</label>
                                                            <select name="department_id" id="department_idEdit{{ $user->id }}" onchange="updateFieldSelectEdit({{ json_encode($user) }})" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                                                <option value="{{ $user->field->department->id }}">{{ $user->field->department->name }}</option>
                                                                @foreach ($departments as $department)
                                                                    @if ($department->id !== $user->field->department->id)
                                                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>


                                                        <div class="mb-4" id="fieldSelectWrapperEdit{{ $user->id }}">
                                                            <label for="field_idEdit{{ $user->id }}" class="block text-sm font-medium text-gray-700">Filière</label>
                                                            <select name="field_id" id="field_idEdit{{ $user->id }}"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                                                <option value="{{ $user->field->id }}">{{ $user->field->name }}</option>
                                                                @foreach ($fields as $field)
                                                                    @if ($field->id !== $user->field_id)
                                                                        <option value="{{ $field->id }}">{{ $field->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-4" id="groupeSelectWrapperEdit">
                                                            <label for="groupe_idEdit" class="block text-sm font-medium text-gray-700">Groupe</label>
                                                            <select name="groupe" id="groupe_idEdit"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                                                <option value="{{ $user->groupe }}">Groupe {{ $user->groupe }}</option>
                                                                @for ($i = 1; $i <= 4; $i++)
                                                                    @if ($i != $user->groupe)
                                                                        <option value="{{ $i }}">Groupe {{ $i }}</option>
                                                                    @endif
                                                                @endfor
                                                            </select>
                                                        </div>




                                                        <div class="mb-4">
                                                            <label for="email"
                                                                class="block text-sm font-medium text-gray-700">Email</label>
                                                            <input type="email" name="email" id="email"
                                                                autocomplete="email"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black"
                                                                value="{{ $user->email }}">
                                                        </div>
                                                        <div class="mb-4">
                                                            <label for="password"
                                                                class="block text-sm font-medium text-gray-700">Mot de
                                                                passe</label>
                                                            <input type="password" name="password" id="password"
                                                                autocomplete="password"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">
                                                        </div>


                                                        <div
                                                            class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <button type="submit"
                                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Modifier</button>
                                                            <button type="button" @click="editModal = false"
                                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- Modal Footer -->
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </a>
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            <a href="#" class="text-gray-400 hover:text-gray-500">

                                <div x-data="{ deleteModal: false, userId: {{ $user->id }} }">
                                    <button @click="deleteModal = true"
                                        class="px-4 py-1 text-sm text-red-400 bg-red-200 rounded-full">
                                        <x-jet-bar-icon type="trash" fill />

                                    </button>
                                    <!-- Delete Modal -->
                                    <div x-show="deleteModal" class="fixed z-10 inset-0 overflow-y-auto"
                                        style="display: none;">
                                        <div
                                            class="d-flex align-items-center justify-content-center min-vh-100 px-4 pt-4 pb-5 text-center">
                                            <div class="fixed inset-0 transition-opacity">
                                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                            </div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                aria-hidden="true">&#8203;</span>
                                            <div
                                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                <div class="bg-gray-100 px-4 py-3 sm:px-6">
                                                    <h3 class="text-lg font-medium text-gray-900">Confirmer suppression</h3>
                                                </div>
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <form action="{{ route('admin.users.destroyStudent', ['user' => $user->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <p class="text-sm text-gray-500">Etes-vous sûr que vous voulez supprimer
                                                            cet étudiant?</p>
                                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                                            <button type="submit"
                                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                            Oui
                                                          </button>
                                                            <button type="button" @click="deleteModal = false"
                                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annuler</button>
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
var fields = @json($fields);
            setTimeout(function() {
        document.getElementById('alert').style.display = 'none';
    }, 5000);
    function hideAlert() {
        document.getElementById('alert').style.display = 'none';
    }

    document.getElementById('department_id').addEventListener('change', updateFieldSelect);
    document.getElementById('field_id').addEventListener('change', updateGroupeSelect);

    function updateFieldSelect() {


        var selectedDepartmentId = document.getElementById('department_id').value;
        var fieldSelectWrapper = document.getElementById('fieldSelectWrapper');
        var fieldSelect = document.getElementById('field_id');

        fieldSelect.disabled = selectedDepartmentId === '';
        fieldSelectWrapper.style.display = selectedDepartmentId === '' ? 'none' : '';

        // Clear the current list of fields
        fieldSelect.innerHTML = '<option value="">Toutes les filières</option>';

        // If a department is selected, filter and append the fields belonging to that department
        if (selectedDepartmentId !== '') {
            @foreach ($fields as $field)
            if ('{{ $field->department_id }}' === selectedDepartmentId) {
                var option = document.createElement('option');
                option.value = '{{ $field->id }}';
                option.textContent = '{{ $field->name }}';
                fieldSelect.appendChild(option);
            }
            @endforeach
        }
    }

    function updateGroupeSelect() {
        var selectedFieldId = document.getElementById('field_id').value;
        var groupeSelectWrapper = document.getElementById('groupeSelectWrapper');
        var groupeSelect = document.getElementById('groupe_id');

        // Enable groupe select if a field is selected
        if (selectedFieldId !== '') {
            groupeSelect.disabled = false;
            groupeSelectWrapper.style.display = '';
        } else {
            groupeSelect.disabled = true;
            groupeSelectWrapper.style.display = 'none';
        }
    }


//edit
function updateFieldSelectEdit(user) {
    const userId = user.id;
    var selectedDepartmentIdEdit = document.getElementById('department_idEdit' + userId).value; // Get the selected department ID
    var currentDepartment = user.field.department_id;
    var currentField = user.field_id;

    var fieldSelectEdit = document.getElementById('field_idEdit' + userId); // Correctly reference the HTML select element

    // Clear existing options
    fieldSelectEdit.innerHTML = '';

    // If selectedDepartmentIdEdit is not empty, update the field options
    if (selectedDepartmentIdEdit !== '') {
        // Iterate over fields and add options belonging to the selected department
        fields.forEach(function(field) {
            if (field.department_id == selectedDepartmentIdEdit) { // Use '==' for loose comparison
                var option = new Option(field.name, field.id);
                fieldSelectEdit.appendChild(option);
            }
        });
    }

    // Set the value of the field select element
    fieldSelectEdit.value = currentField;
}


        </script>


    </x-jet-bar-container>
</x-app-layout>
