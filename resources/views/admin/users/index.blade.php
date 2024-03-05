<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users List') }}
        </h2>
    </x-slot>

    <div>
        <br> <br>
        <div class="container flex justify-center mx-auto">
            <div class="flex flex-col">
                <div class="w-full">
                    <div class="p-12 border-b border-gray-200 shadow">

                        <div x-data="{ openModal: false }">
                            <!-- Button to open modal -->
                            <button @click="openModal = true"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                style="float: right">Add New User</button>
                            <br><br>


                            <!-- Modal -->
                            <div x-show="openModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
                                <div
                                    class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
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
                                            <h3 class="text-lg font-medium text-gray-900">Add New User</h3>
                                        </div>

                                        <!-- Modal Body -->
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <!-- Your form content goes here -->
                                            <form action="{{ route('admin.users.create') }}" method="put">
                                                @csrf
                                                <div class="mb-4">
                                                    <label for="role"
                                                        class="block text-sm font-medium text-gray-700">Role</label>
                                                    <select name="role" id="role"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                        <option value=1>Admin</option>
                                                        <option value=2>Teacher</option>
                                                        <option value=3>Student</option>
                                                    </select>
                                                </div>

                                                <div class="mb-4">
                                                    <label for="name"
                                                        class="block text-sm font-medium text-gray-700">Name</label>
                                                    <input type="text" name="name" id="name" autocomplete="name"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>

                                                <div class="mb-4">
                                                    <label for="email"
                                                        class="block text-sm font-medium text-gray-700">Email</label>
                                                    <input type="email" name="email" id="email" autocomplete="email"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="password"
                                                        class="block text-sm font-medium text-gray-700">Password</label>
                                                    <input type="password" name="password" id="password"
                                                        autocomplete="password"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>

                                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                    <button type="submit"
                                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">Create</button>
                                                    <button type="button" @click="openModal = false"
                                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Modal Footer -->

                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Table -->
                        <table class="divide-y divide-gray-300" id="dataTable">
                            <!-- Table headers -->
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-2 text-xs text-gray-500">ID</th>
                                    <th class="px-6 py-2 text-xs text-gray-500">Name</th>
                                    <th class="px-6 py-2 text-xs text-gray-500">Role</th>
                                    <th class="px-6 py-2 text-xs text-gray-500">Email</th>
                                    <th class="px-6 py-2 text-xs text-gray-500">Created_at</th>
                                    <th class="px-6 py-2 text-xs text-gray-500">Edit</th>
                                    <th class="px-6 py-2 text-xs text-gray-500">Delete</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-500">
                                <!-- Table rows -->
                                @foreach($users as $user)
                                <tr class="whitespace-nowrap">
                                    <td class="px-6 py-4 text-sm text-center text-gray-500">{{ $user->id }}</td>
                                    <td class="px-6 py-4 text-center">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($user->role_id == 1)
                                        Admin
                                        @elseif($user->role_id == 3)
                                        Teacher
                                        @elseif($user->role_id == 2)
                                        Student
                                        @endif</td>
                                    <td class="px-6 py-4 text-center">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm text-center text-gray-500">{{ $user->created_at }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <!-- Edit User Modal -->
<div x-data="{ editModal: false, editUserId: {{ $user->id }} }">
    <!-- Button to open modal -->
    <button @click="editModal = true"
        class="px-4 py-1 text-sm text-blue-600 bg-blue-200 rounded-full">Edit</button>

    <!-- Modal -->
    <div x-show="editModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="bg-gray-100 px-4 py-3 sm:px-6">
                    <h3 class="text-lg font-medium text-gray-900">Edit User</h3>
                </div>

                <!-- Modal Body -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Your form content goes here -->
                    <form action="{{ route('admin.users.update', ['user' => $user->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="1">Admin</option>
                                <option value="2">Teacher</option>
                                <option value="3">Student</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" autocomplete="name"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" autocomplete="email"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" autocomplete="password"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">Update</button>
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

                                    </td>


                                    <td class="px-6 py-4 text-center">
                                        <div x-data="{ deleteModal: false, userId: {{ $user->id }} }">
                                            <button @click="deleteModal = true" class="px-4 py-1 text-sm text-red-400 bg-red-200 rounded-full">Delete</button>
                                            <!-- Delete Modal -->
                                            <div x-show="deleteModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
                                                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                                    <div class="fixed inset-0 transition-opacity">
                                                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                                    </div>
                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                        <div class="bg-gray-100 px-4 py-3 sm:px-6">
                                                            <h3 class="text-lg font-medium text-gray-900">Confirm Deletion</h3>
                                                        </div>
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <form :action="'{{ route('admin.users.destroy', '') }}/' + userId" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <p class="text-sm text-gray-500">Are you sure you want to delete this user?</p>
                                                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                                                    <button type="submit"
                                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">Yes</button>
                                                                    <button type="button" @click="deleteModal = false"
                                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End of Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-admin-layout>
