<x-app-layout>
    <x-slot name="header">
        Actualités
    </x-slot>

    <x-jet-bar-container>
        @if (auth()->user()->role_id == 1)
        <x-jet-bar-stats-container>
            <x-jet-bar-stat-card title="Total Etudiants" number="{{ $Nbstudents }}" type="warning" >
                <x-jet-bar-icon type="users" fill />
            </x-jet-bar-stat-card>
            <!-- Card -->
            <x-jet-bar-stat-card title="Total Enseignants" number="{{ $Nbteachers }}" type="success" >
                <x-jet-bar-icon type="users" fill />
            </x-jet-bar-stat-card>
            <!-- Card -->
            <x-jet-bar-stat-card title="Total Départements" number="{{ $Nbdep }}" type="info" >
                <x-jet-bar-icon type="information-circle" fill />
            </x-jet-bar-stat-card>
            <!-- Card -->
            <x-jet-bar-stat-card title="Total Filiéres" number="{{ $Nbfields }}" type="danger" >
                <x-jet-bar-icon type="check-circle" fill />
            </x-jet-bar-stat-card>
        </x-jet-bar-stats-container>
        @endif
        <div class="list-group">
            @foreach ($news as $new)
            <a href="{{ route('indexNew', ['id' => $new->id]) }}" class="list-group-item list-group-item-action" aria-current="true">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-5">{{ $new->title }}</h5>
                  <small>{{ $new->created_at }}</small> <!-- Correction: $new->created_at -->
                </div>
              </a>
            @endforeach
        </div>
    </x-jet-bar-container>
</x-app-layout>
