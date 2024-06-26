<x-jet-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Information Profil') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Modifier les informations de profil de votre compte') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                            wire:model="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-jet-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview">
                    <span class="block rounded-full w-20 h-20"
                          x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-jet-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-jet-secondary-button>
                @endif

                <x-jet-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="last_name" value="{{ __('Nom') }}" />
            <x-jet-input id="last_name" type="text" class="mt-1 block w-full" wire:model.defer="state.last_name" autocomplete="last_name" />
            <x-jet-input-error for="last_name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Prénom') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autocomplete="name" />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        @if ($this->user->role_id==2)


        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="department" value="{{ __('Département') }}" />
            <x-jet-input id="department" type="text" class="mt-1 block w-full" value="{{ $this->user->field->department->name }}" autocomplete="department" disabled style="background-color: #f2f2f2; color: #555555;" />
            <x-jet-input-error for="department" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="field" value="{{ __('Filiére') }}" />
            <x-jet-input id="field" type="text" class="mt-1 block w-full" value="{{ $this->user->field->name }}" autocomplete="field" disabled style="background-color: #f2f2f2; color: #555555;" />
            <x-jet-input-error for="field" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="groupe" value="{{ __('Groupe') }}" />
            <x-jet-input id="groupe" type="text" class="mt-1 block w-full" value="{{ $this->user->groupe }}" autocomplete="field" disabled style="background-color: #f2f2f2; color: #555555;" />
            <x-jet-input-error for="groupe" class="mt-2" />
        </div>

        @endif


        @if ($this->user->role_id==3)
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="subjects" value="{{ __('Matiéres') }}" />
            <x-jet-input id="subjects"
                   type="text"
                   class="mt-1 block w-full"
                   wire:input="state.subjects"
                   autocomplete="subjects"
                   disabled
                   style="background-color: #f2f2f2; color: #555555;"
                   value="{{ implode(', ', $this->user->subjects->pluck('name')->toArray()) }}" />
            <x-jet-input-error for="subjects" class="mt-2" />
        </div>




        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="Nbureau" value="{{ __('Num bureau') }}" />
            <x-jet-input id="Nbureau" type="text" class="mt-1 block w-full" wire:model.defer="state.Nbureau" autocomplete="Nbureau" disabled style="background-color: #f2f2f2; color: #555555;" />
            <x-jet-input-error for="Nbureau" class="mt-2" />
        </div>
        @endif

        @if ($this->user->role_id==2 || $this->user->role_id==3)
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="phone" value="{{ __('Téléphone') }}" />
            <x-jet-input id="phone" type="text" class="mt-1 block w-full" wire:model.defer="state.phone" autocomplete="phone" />
            <x-jet-input-error for="phone" class="mt-2" />
        </div>


        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="address" value="{{ __('Adresse') }}" />
            <x-jet-input id="address" type="text" class="mt-1 block w-full" wire:model.defer="state.address" autocomplete="address" />
            <x-jet-input-error for="address" class="mt-2" />
        </div>
        @endif

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="email" value="{{ __('Email') }}" />
            <x-jet-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
            <x-jet-input-error for="email" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Enregistré.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Sauvegarder') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
