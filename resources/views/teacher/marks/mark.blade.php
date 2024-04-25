<x-app-layout>
    <x-slot name="header">
        Semestre: {{ $semester }} <br>
        Filiére: {{ $getSubject->field->name }} <br>
        Matiére: {{ $getSubject->name }} <br>
        Etudiant: {{ $getStudent->name }} {{ $getStudent->last_name }}
    </x-slot>

    <x-jet-bar-container>
        @if ($marks->isEmpty())
        <div>
            @if ($marks->isEmpty())
                <div x-data="{ openModal: {{ $errors->any() && session('form_type') === 'create' ? 'true' : 'false' }}}">
                    <!-- Button to open modal -->
                    <button @click="openModal = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">
                        <x-jet-bar-icon type="plus" fill />
                    </button>
                    <br><br>

                    <!-- Modal -->
                    <div x-show="openModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-5 text-center">
                            <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <div class="bg-gray-100 px-4 py-3 sm:px-6">
                                    <h3 class="text-lg font-medium text-gray-900">Ajouter Notes</h3>
                                </div>
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <!-- Your form content goes here -->
                                    <form action="{{ route('teacher.marks.create') }}" method="POST">
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
                                            <label for="note_exam" class="block text-sm font-medium text-gray-700">Note examen</label>
                                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="note_exam" type="text" name="note_exam">
                                        </div>
                                        <div class="mb-4">
                                            <label for="note_ds" class="block text-sm font-medium text-gray-700">Note DS</label>
                                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="note_ds" type="text" name="note_ds">
                                        </div>
                                        <div class="mb-4">
                                            <label for="note_tp" class="block text-sm font-medium text-gray-700">Note TP</label>
                                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="note_tp" type="text" name="note_tp">
                                        </div>
                                        <input type="hidden" name="semester" value="{{ $semester }}">
                                        <input type="hidden" name="student_id" value="{{ $student }}">
                                        <input type="hidden" name="subject_id" value="{{ $subject }}">
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Ajouter</button>
                                            <button type="button" @click="openModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annuler</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <x-jet-bar-alert type="success" text="Notes ajoutées avec succès !" />
            @endif

            @if(session('warning'))
                <x-jet-bar-alert type="warning" text="Notes modifiées avec succès !" />
            @endif

            <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($marks as $mark)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $mark->note_exam }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $mark->note_ds }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $mark->note_tp }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">



                                    <div x-data="{ editModal: {{ $errors->any() && session('edit_mark_id') == $mark->id && session('form_type') === 'edit' ? 'true' : 'false' }}}">
                                        <!-- Button to open modal -->
                                        <button @click="editModal = true" class="px-4 py-1 text-sm text-blue-600 bg-blue-200 rounded-full">
                                            <x-jet-bar-icon type="pencil" fill />
                                        </button>
                                        <!-- Modal -->
                                        <div x-show="editModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
                                            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-5 text-center">
                                                <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                    <!-- Modal Header -->
                                                    <div class="bg-gray-100 px-4 py-3 sm:px-6">
                                                        <h3 class="text-lg font-medium text-gray-900">Modifier Notes</h3>
                                                    </div>
                                                    <!-- Modal Body -->
                                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                        <!-- Your form content goes here -->
                                                        <form action="{{ route('teacher.marks.update', ['id' => $mark->id]) }}" method="POST">
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
                                                                <label for="note_exam" class="block text-sm font-medium text-gray-700">Note examen</label>
                                                                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="note_exam" type="text" name="note_exam" value="{{ $mark->note_exam }}">
                                                            </div>
                                                            <div class="mb-4">
                                                                <label for="note_ds" class="block text-sm font-medium text-gray-700">Note DS</label>
                                                                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="note_ds" type="text" name="note_ds" value="{{ $mark->note_ds }}">
                                                            </div>
                                                            <div class="mb-4">
                                                                <label for="note_tp" class="block text-sm font-medium text-gray-700">Note TP</label>
                                                                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="note_tp" type="text" name="note_tp" value="{{ $mark->note_tp }}">
                                                            </div>
                                                            <input type="hidden" name="semester" value="{{ $semester }}">
                                                            <input type="hidden" name="student_id" value="{{ $student }}">
                                                            <input type="hidden" name="subject_id" value="{{ $subject }}">
                                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                <button type="submit" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Modifier</button>
                                                                <button type="button" @click="editModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annuler</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <!-- Display other marks as needed -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @endif









        @if(session('success'))
        <x-jet-bar-alert type="success" text="Notes ajoutées avec succès !" />

        @endif

        @if(session('warning'))
        <x-jet-bar-alert type="warning" text="Notes modifiées avec succès !" />

        @endif









{{-- edit --}}
        <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note
                            Examen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note
                            Ds</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note
                            Tp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        <!-- Add more headers as needed -->
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($marks as $mark)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $mark->note_exam }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $mark->note_ds }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $mark->note_tp }}</td>
                        <td>
                            <a href="#" class="text-gray-400 hover:text-gray-500">


                                <div
                                    x-data="{ editModal: {{ $errors->any() &&  session('edit_mark_id') == $mark->id  &&  session('form_type') === 'edit'? 'true' : 'false' }}, editmarkId: {{ $mark->id }} }">
                                    <!-- Button to open modal -->
                                    <div class="flex justify-center items-center">
                                        <button @click="editModal = true"
                                                class="px-4 py-1 text-sm text-yellow-600 bg-yellow-200 rounded-full">
                                            <x-jet-bar-icon type="pencil" fill />
                                        </button>
                                    </div>



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
                                                    <h3 class="text-lg font-medium text-gray-900">Modifier Notes
                                                    </h3>
                                                </div>

                                                <!-- Modal Body -->
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <!-- Your form content goes here -->
                                                    <form
                                                        action="{{ route('teacher.marks.update', ['id' => $mark->id]) }}"
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
                                                            <label for="note_exam"
                                                                class="block text-sm font-medium text-gray-700">Note
                                                                examen</label>
                                                            <input
                                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                                id="note_exam" type="text" name="note_exam"
                                                                value="{{ $mark->note_exam }}">
                                                        </div>

                                                        <div class="mb-4">
                                                            <label for="note_ds"
                                                                class="block text-sm font-medium text-gray-700">Note
                                                                DS</label>
                                                            <input
                                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                                id="note_ds" type="text" name="note_ds"
                                                                value="{{ $mark->note_ds }}">
                                                        </div>

                                                        <div class="mb-4">
                                                            <label for="note_tp"
                                                                class="block text-sm font-medium text-gray-700">Note
                                                                TP</label>
                                                            <input
                                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                                id="note_tp" type="text" name="note_tp"
                                                                value="{{ $mark->note_tp }}">
                                                        </div>

                                                        <input hidden
                                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                            id="semester" type="text" name="semester"
                                                            value="{{ $semester }}">
                                                        <input hidden
                                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                            id="student_id" type="text" name="student_id"
                                                            value="{{ $student }}">
                                                        <input hidden
                                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                            id="subject_id" type="text" name="subject_id"
                                                            value="{{ $subject }}">


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
                        </td>
                        <!-- Display other marks as needed -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
                font-size: 1.5rem;
                /* Adjust the size as needed */
                vertical-align: middle;
                margin-left: 5px;
                /* Adjust the spacing as needed */
            }
        </style>
    </x-jet-bar-container>
</x-app-layout>
