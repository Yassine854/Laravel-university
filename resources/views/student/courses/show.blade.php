<x-app-layout>


    <x-slot name="header">
        Supports de cours <br> <br>
        Matiétre : {{ $subject->name }}
    </x-slot>

    <x-jet-bar-container>


        <div>


            <x-jet-bar-table :headers="['ID','Titre','Created_at','']" class="flex justify-center">
                <template x-data="{ total:1 }" x-for="index in total">
                    @foreach($courses as $course)
                    <tr class="hover:bg-gray-50">
                        <x-jet-bar-table-data>
                            {{ $course->id }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            {{ $course->title }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>
                            {{ $course->created_at }}
                        </x-jet-bar-table-data>

                        <x-jet-bar-table-data>

                            <!-- Add a link to download the course -->

                            <a href="{{ route('student.courses.downloadCourse', ['fileName' => $course->file]) }}"
                                target="_blank"
                                class="inline-block bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Télécharger</a>



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
