@php
    $title = null;

    $adminOrHeadOfDepartmentRole = ['admin', 'HoD', 'super-admin'];
    $isAdminOrHeadOfDepartment = in_array(auth()->user()->role, $adminOrHeadOfDepartmentRole) ? true : false;

    if ($isAdminOrHeadOfDepartment) {
        $title = 'Manajemen Pengumuman';
    } else {
        $title = 'Pengumuman';
    }
@endphp

<x-dashboard-layout title="{{ $title }}">
    <x-slot name="header">
        {{ $title }}
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Pengumuman</h5>
            @if ($isAdminOrHeadOfDepartment)
                <a href="{{ route('dashboard.announcements.create') }}" class="btn btn-primary me-4">Buat Pengumuman</a>
            @endif
        </div>
        @if ($isAdminOrHeadOfDepartment)
            <div class="table-responsive text-nowrap px-4 pb-4">
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th class="text-center">Judul Pengumuman</th>
                            <th class="text-center">Tanggal Dibuat</th>
                            <th class="text-center">Dibuat Oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($announcements as $announcement)
                            <tr>
                                <td class="fw-medium">{{ $announcement->title }}</td>
                                <td class="text-center">{{ $announcement->created_at->diffForHumans() }}</td>
                                <td class="text-center">{{ $announcement->user->name }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.announcements.show', $announcement->slug) }}">
                                                <i class="bx bxs-user-detail me-1"></i> Detail
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.announcements.edit', $announcement->slug) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.announcements.destroy', $announcement->slug) }}"
                                                data-confirm-delete="true">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        @php
            $announcements = $announcements->take(10);
        @endphp
            <div class="px-4 pb-4">
                @foreach ($announcements as $announcement)
                    <div class="border rounded {{ $loop->last ? 'mb-0' : ' mb-2' }}">
                        <div class="card-body px-4 py-3">
                            <div class="blockquote mb-0 pb-0">
                                <a href="{{ route('dashboard.announcements.show', $announcement->slug) }}">
                                    <h4 class="text-primary mt-0 pt-0">
                                        {{ $announcement->title }}
                                    </h4>
                                    <p class="blockquote-footer my-0">Dibuat oleh: {{ $announcement->user->name }} |
                                        {{ date('j F Y', strtotime($announcement->created_at)) }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-dashboard-layout>
