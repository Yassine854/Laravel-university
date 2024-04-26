<x-app-layout>


    <x-slot name="header">
        Formulaires en ligne
    </x-slot>

    <x-jet-bar-container>


        <div>

            @if(session('warning'))
            <x-jet-bar-alert type="warning" text="État modifiée avec succès !" />

            @endif





            <x-jet-bar-table :headers="['Titre','Description','Etudiant','Filiére','État','Created_at','','']"
                class="flex justify-center">
                <template x-data="{ total:1 }" x-for="index in total">
                    @foreach($forms as $form)
                    <tr class="hover:bg-gray-50">

                        <x-jet-bar-table-data>
                            {{ $form->titre }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            {{ $form->description }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            {{ $form->student->name }} {{ $form->student->last_name }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            {{ $form->student->field->name }} G{{ $form->student->groupe }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            @if($form->state == 'En cours')
                            <x-jet-bar-badge text="En cours" type="warning" />
                            @elseif($form->state == 'Acceptée')
                            <x-jet-bar-badge text="Acceptée" type="success" />
                            @elseif($form->state == 'Refusée')
                            <x-jet-bar-badge text="Refusée" type="danger" />
                            @endif
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            {{ $form->created_at }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>

                            <!-- Add a link to download the form -->

                            <a href="{{ route('admin.forms.download', ['fileName' => $form->file]) }}" target="_blank"
                                class="inline-block bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Télécharger</a>



                        </x-jet-bar-table-data>


                        <x-jet-bar-table-data>
                            <a href="#" class="text-gray-400 hover:text-gray-500">

                                {{-- edit form --}}
                                <div
                                    x-data="{ editModal: {{ $errors->any() && session('edit_form_id') == $form->id && session('form_type') === 'edit' ? 'true' : 'false' }}, editformId: {{ $form->id }} }">
                                    <!-- Button to open modal -->
                                    <button @click="editModal = true"
                                        class="px-4 py-1 text-sm text-yellow-600 bg-yellow-200 rounded-full">
                                        <x-jet-bar-icon type="pencil-alt" fill />
                                    </button>

                                    <!-- Modal -->
                                    <div x-show="editModal" class="fixed z-10 inset-0 overflow-y-auto">
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
                                                    <h3 class="text-lg font-medium text-gray-900">Modifier État</h3>
                                                </div>

                                                <!-- Modal Body -->
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <!-- Your form content goes here -->
                                                    <form
                                                        action="{{ route('admin.forms.update', ['id'=>$form->id]) }}"
                                                        method="POST" enctype="multipart/form-data">
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

                                                        <div class="mb-4" >
                                                            <label for="state" class="block text-sm font-medium text-gray-700">État</label>
                                                            <select name="state" id="state"
                                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-black">

                                                                @if($form->state=="Acceptée")
                                                                <option value="{{ $form->state }}">{{ $form->state }}</option>
                                                                <option value="Refusée">Refusée</option>
                                                                @elseif($form->state=="Refusée")
                                                                <option value="{{ $form->state }}">{{ $form->state }}</option>
                                                                <option value="Acceptée">Acceptée</option>
                                                                @elseif($form->state=="En cours")
                                                                <option value="">Sélectionner l'état</option>
                                                                <option value="Acceptée">Acceptée</option>
                                                                <option value="Refusée">Refusée</option>
                                                                @endif
                                                            </select>
                                                        </div>

                                                        <div
                                                            class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <button type="submit"
                                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Modifier</button>
                                                            <button type="button" @click="editModal = false"
                                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annuler</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    function updateFileName(event) {
        var fileName = event.target.files[0].name;
        document.getElementById('oldFileName').value = fileName;
    }
                                </script>




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

        </script>


    </x-jet-bar-container>
</x-app-layout>
