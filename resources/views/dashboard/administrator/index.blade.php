<x-dashboard-layout title="Manajemen Mahasiswa">
    <x-slot name="header">
        Manajemen Administrator
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Administrator</h5>
            <a href="{{ route('dashboard.administrator.create') }}" class="btn btn-primary me-4">Tambah Administrator</a>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Terakhir Dilihat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($administrators as $administrator)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $administrator->name }}</td>
                            <td class="text-center">{{ $administrator->username }}</td>
                            <td class="text-center">{{ $administrator->email }}</td>
                            <td class="text-center">
                                @if ($administrator->isOnline())
                                    <span class="badge text-bg-primary">Online</span>
                                @else
                                    <span class="badge text-bg-secondary">{{ $administrator->lastActivityAgo() }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if (auth()->user()->role == 'super-admin')
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.administrator.show', $administrator->id) }}">
                                                <i class="bx bxs-user-detail me-1"></i> Detail
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.administrator.edit', $administrator->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.administrator.destroy', $administrator->id) }}"
                                                data-confirm-delete="true">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                @elseif(auth()->user()->id == $administrator->id)
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.administrator.show', $administrator->id) }}">
                                                <i class="bx bxs-user-detail me-1"></i> Detail
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.administrator.edit', $administrator->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <a class="dropdown-item"
                                        href="{{ route('dashboard.administrator.show', $administrator->id) }}">
                                        <i class="bx bxs-user-detail me-1"></i> Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
