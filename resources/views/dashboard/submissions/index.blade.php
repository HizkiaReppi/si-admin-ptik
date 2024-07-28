<x-dashboard-layout title="Manajemen Pengajuan">
    <x-slot name="header">
        Manajemen Pengajuan
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Manajemen Pengajuan Surat</h5>
            <a href="{{ route('dashboard.submission.create') }}" class="btn btn-primary me-4">Ajukan Surat</a>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th class="text-center">Nama</th>
                        <th class="text-center">NIM</th>
                        <th class="text-center">Tipe Pengajuan</th>
                        <th class="text-center">Waktu Pengajuan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($submissions as $submission)
                        <tr>
                            <td class="fw-medium">{{ $submission->student->fullname }}</td>
                            <td class="text-center text-nowrap">{{ $submission->student->formattedNIM }}</td>
                            <td class="text-center">{{ $submission->category->name }}</td>
                            <td class="text-center">{{ $submission->created_at->diffForHumans() }}</td>
                            <td class="text-center">{{ parseSubmissionStatus($submission->status) }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.submission.show', $submission->id) }}">
                                            <i class="bx bxs-user-detail me-1"></i> Detail
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.submission.edit', $submission->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.submission.destroy', $submission->id) }}"
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
