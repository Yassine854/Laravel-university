<header class="hidden lg:flex justify-between items-center py-4 px-6 bg-white border-b border-gray-200">
    {{-- Searchbar --}}
    <div class="flex items-center">
        <div class="relative mr-4 lg:mx-0">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">

            </span>

            <input class="form-input w-32 sm:w-64 rounded-md pl-10 pr-4 p-2 bg-gray-100 focus:bg-transparent focus:border-indigo-300" type="text" placeholder="Rechercher">
        </div>
    </div>
    {{-- End Searchbar --}}

    <div class="flex items-center">
        {{-- Notifications --}}
        @if (auth()->user()->role_id == 1)
        @php
        $unreadNotificationsCount = auth()->user()->unreadNotifications->count();
    @endphp

        <div x-data="{ notificationOpen: false }" class="relative">
            <button @click="notificationOpen = ! notificationOpen"
                    class="flex mx-4 text-gray-600 focus:outline-none">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M15 17H20L18.5951 15.5951C18.2141 15.2141 18 14.6973 18 14.1585V11C18 8.38757 16.3304 6.16509 14 5.34142V5C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5V5.34142C7.66962 6.16509 6 8.38757 6 11V14.1585C6 14.6973 5.78595 15.2141 5.40493 15.5951L4 17H9M15 17V18C15 19.6569 13.6569 21 12 21C10.3431 21 9 19.6569 9 18V17M15 17H9"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                </svg>
                @if ($unreadNotificationsCount == 0)
                <span class="bg-red-500 text-white rounded-full px-1 py-0.8 absolute bottom-3 right-4 text-xs">0</span>
                @endif
                @if ($unreadNotificationsCount > 0)
                <span class="bg-red-500 text-white rounded-full px-1 py-0.8 absolute bottom-3 right-4 text-xs">{{ $unreadNotificationsCount }}</span>
                @endif
        </button>

            <div x-show="notificationOpen" @click="notificationOpen = false" class="fixed inset-0 h-full w-full z-10" style="display: none;"></div>

            <div x-show="notificationOpen" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl overflow-hidden z-10 border" style="width: 20rem; display: none;">
                @if(auth()->user()->unreadNotifications->isEmpty())
                    <p class="py-3 px-4 text-gray-600">Aucune notification trouvée.</p>
                @else
                    @foreach(auth()->user()->unreadNotifications as $notification)
                    <form action="{{ route('admin.markNotificationAsRead', $notification->id) }}" method="POST" class="hover:bg-gray-100">
                        @csrf
                        <button type="submit" class="flex items-center px-4 py-3 w-full text-left text-gray-600 hover:bg-gray-100 focus:outline-none">
                            <div class="bg-gray-200 rounded-full p-2 mr-3">
                                <!-- You can add an icon here if you have one -->
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Nouvelle formulaire ajoutée</p>
                                <p class="text-xs text-gray-600">Sujet: {{ $notification->data['form_title'] }}</p>
                                <p class="text-xs text-gray-600">{{ $notification->created_at }}</p>
                            </div>
                        </button>
                    </form>
                    @endforeach
                @endif
            </div>

        </div>
        @endif
        {{-- End Notifications --}}

        {{-- User Dropdown --}}

        <div x-data="{ dropdownOpen: false }" class="relative">

            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <button @click="dropdownOpen = ! dropdownOpen" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                </button>
            @else
                <span class="inline-flex rounded-md">
                    <button @click="dropdownOpen = ! dropdownOpen" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        {{ Auth::user()->name }} {{ Auth::user()->last_name }}

                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
            @endif

            <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 h-full w-full z-10" style="display: none;"></div>

            <div x-show="dropdownOpen"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10 border"
                 style="display: none;">

                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-jet-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                        {{ __('Team Settings') }}
                    </x-jet-dropdown-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-jet-dropdown-link href="{{ route('teams.create') }}">
                            {{ __('Create New Team') }}
                        </x-jet-dropdown-link>
                    @endcan

                    <div class="border-t border-gray-100"></div>

                    <!-- Team Switcher -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Switch Teams') }}
                    </div>

                    @foreach (Auth::user()->allTeams() as $team)
                        <x-jet-switchable-team :team="$team" />
                    @endforeach

                    <div class="border-t border-gray-100"></div>
                @endif

                <!-- Account Management -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Gérer profil') }}
                </div>

                <x-jet-dropdown-link href="{{ route('profile.show') }}">
                    {{ __('Profil') }}
                </x-jet-dropdown-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                        {{ __('API Tokens') }}
                    </x-jet-dropdown-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-jet-dropdown-link href="{{ route('logout') }}"
                                         onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-jet-dropdown-link>
                </form>
            </div>

        </div>
        {{-- End User Dropdown --}}

    </div>
</header>
