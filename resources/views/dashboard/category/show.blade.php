<x-dashboard-layout title="Manajemen Pengajuan">
    <x-slot name="header">
        Manajemen Pengajuan
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Pengajuan {{ $kategori->name }}</h5>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th class="text-center">NIM</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Waktu Pengajuan</th>
                        <th class="text-center">Terakhir Diubah</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($submissions as $submission)
                        <tr>
                            <td class="text-center text-nowrap">{{ $submission->student->formattedNIM }}</td>
                            <td class="fw-medium">{{ $submission->student->fullname }}</td>
                            <td class="text-center">{{ $submission->created_at->diffForHumans() }}</td>
                            <td class="text-center">{{ $submission->updated_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <span
                                    class="badge text-bg-{{ parseSubmissionBadgeClassNameStatus($submission->status) }}">
                                    {{ parseSubmissionStatus($submission->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php
                                    $statuses = ['rejected', 'canceled', 'expired'];
                                    $isStatusInArray = in_array($submission->status, $statuses);
                                    $isStatusDoneAndOld =
                                        $submission->status == 'done' && $submission->updated_at->lt(now()->subDays(7));
                                @endphp
                                @if ($isStatusInArray || $isStatusDoneAndOld)
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
                                                href="{{ route('dashboard.submission.destroy', $submission->id) }}"
                                                data-confirm-delete="true">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <a class="dropdown-item"
                                        href="{{ route('dashboard.submission.show', $submission->id) }}">
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
