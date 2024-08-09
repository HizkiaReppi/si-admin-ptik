<x-dashboard-layout title="Manajemen Pimpinan Jurusan">
    <x-slot name="header">
        Manajemen Pimpinan Jurusan
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Pimpinan Jurusan</h5>
            <div class="dropdown">
                <button class="btn btn-primary me-4 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Aksi
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('dashboard.pimpinan-jurusan.kajur.create') }}">
                            {{ $kajur ? 'Ganti' : 'Tambah' }} Ketua Jurusan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('dashboard.pimpinan-jurusan.sekjur.create') }}">
                            {{ $sekjur ? 'Ganti' : 'Tambah' }} Sekretaris Jurusan
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">Nama</th>
                        <th class="text-center">NIDN</th>
                        <th class="text-center">NIP</th>
                        <th class="text-center">Peran</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if (!empty($headOfDepartments))
                        @php
                            $sortedHeadOfDepartments = $headOfDepartments->sortByDesc(function($headOfDepartment) {
                                return $headOfDepartment->role == 'kajur';
                            });
                        @endphp
                        @foreach ($sortedHeadOfDepartments as $headOfDepartment)
                            <tr>
                                <td class="fw-medium">{{ $headOfDepartment->fullname }}</td>
                                <td class="text-center text-nowrap">{{ $headOfDepartment->nidn }}</td>
                                <td class="text-center text-nowrap">{{ $headOfDepartment->formattedNIP }}</td>
                                <td class="text-center">
                                    {{ $headOfDepartment->role == 'kajur' ? 'Ketua Jurusan' : 'Sekretaris Jurusan' }}
                                </td>
                                <td class="d-flex justify-content-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('dashboard.pimpinan-jurusan.show', $headOfDepartment->id) }}">
                                                <i class="bx bxs-user-detail me-1"></i> Detail
                                            </a>
                                            <a class="dropdown-item" href="{{ route('dashboard.pimpinan-jurusan.edit', $headOfDepartment->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{ route('dashboard.pimpinan-jurusan.destroy', $headOfDepartment->id) }}"
                                               data-confirm-delete="true">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">Belum Ada Data Pimpinan Jurusan</td>
                        </tr>
                    @endif
                </tbody>
                
            </table>
        </div>
    </div>
</x-dashboard-layout>
