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
        <div class="container-fluid">
            <div class="d-flex justify-content-center my-3">
                <div class="col-md-12">
                    <h1 class="mb-1">{{ $announcement->title }}</h1>
                    <p>Dibuat oleh: {{ $announcement->user->name }} | {{ $announcement->created_at->diffForHumans() }}
                    </p>
                    <hr class="my-2">

                    <article class="my-3 fs-6">
                        {!! $announcement->content !!}
                    </article>

                    <hr class="mt-2 mb-4">
                    <div class="d-flex gap-2" style="margin-left: -7px">
                        <a href="{{ route('dashboard.announcements.index') }}"
                            class="btn btn-outline-secondary ms-2">Kembali</a>
                        @if ($isAdminOrHeadOfDepartment)
                            <a href="{{ route('dashboard.announcements.edit', [$announcement->slug]) }}"
                                class="btn btn-primary">Edit Pengumuman</a>
                            <a class="btn btn-danger"
                                href="{{ route('dashboard.announcements.destroy', $announcement->slug) }}"
                                data-confirm-delete="true">
                                Hapus Pengumuman
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</x-dashboard-layout>
