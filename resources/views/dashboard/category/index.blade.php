<x-dashboard-layout title="Manajemen Kategori">
    <x-slot name="header">
        Manajemen Kategori
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Kategori</h5>
            <a href="{{ route('dashboard.category.create') }}" class="btn btn-primary me-4">Tambah Kategori</a>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Persyaratan</th>
                        <th class="text-center">Total <br> Surat</th>
                        <th class="text-center">Surat <br> Selesai</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($categories as $category)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1 }}</td>
                            <td class="fw-medium">{{ $category->name }}</td>
                            <td class="text-nowrap">
                                @if($category->requirements->isEmpty())
                                    <p class="m-0 text-center">
                                        Tidak Ada Persyaratan
                                    </p>
                                @else
                                    <ul class="mb-0">
                                        @foreach($category->requirements as $requirement)
                                            <li>
                                                @if($requirement->file_path)
                                                    <a href="{{ $requirement->file_path }}" target="_blank">{{ $requirement->name }}</a>
                                                @else
                                                    {{ $requirement->name }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="text-center">{{ $category->submissions->count() }}</td>
                            <td class="text-center">{{ $category->submissions->where('status', 'done')->count() }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.category.show', $category->slug) }}">
                                            <i class="bx bxs-user-detail me-1"></i> Detail
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.category.edit', $category->slug) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.category.destroy', $category->slug) }}"
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
    </div>
</x-dashboard-layout>
