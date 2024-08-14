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
                <table class="table" id="table-announcements">
                    <thead>
                        <tr>
                            <th class="text-center">Judul Pengumuman</th>
                            <th class="text-center">Tanggal Dibuat</th>
                            <th class="text-center">Dibuat Oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0"></tbody>
                </table>
            </div>
        @else
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

    <script>
        $(document).ready(function() {
            $('#table-announcements').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.announcements.index') }}',
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'created_at', name: 'created_at', className: 'text-center' },
                    { data: 'user', name: 'user', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ]
            });
        });
    </script>
</x-dashboard-layout>
