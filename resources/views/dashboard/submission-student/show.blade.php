<x-dashboard-layout title="Manajemen Pengajuan">
    <x-slot name="header">
        Manajemen Pengajuan
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Manajemen Pengajuan {{ $category->name }}</h5>
            <a href="{{ route('dashboard.submission.student.create', $category->slug) }}" class="btn btn-primary me-4">Ajukan Surat</a>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th class="text-center">Waktu Pengajuan</th>
                        <th class="text-center">Terakhir Diubah</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($submissions as $submission)
                        <tr>
                            <td class="text-center">{{ $submission->created_at->diffForHumans() }}</td>
                            <td class="text-center">{{ $submission->updated_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <span class="badge text-bg-{{$submission->parseSubmissionBadgeClassNameStatus}}">
                                    {{ $submission->parseSubmissionStatus }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($submission->status !== 'submitted')
                                    <a class="dropdown-item"
                                        href="{{ route('dashboard.submission.student.detail', [$category->slug, $submission->id]) }}">
                                        <i class="bx bxs-user-detail me-1"></i> Detail
                                    </a>
                                @else
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.submission.student.detail', [$category->slug, $submission->id]) }}">
                                            <i class="bx bxs-user-detail me-1"></i> Detail
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.submission.student.edit', [$category->slug, $submission->id]) }}">
                                            <i class="bx bxs-user-detail me-1"></i> Edit
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.submission.student.destroy', $submission->id) }}"
                                            data-confirm-delete="true">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
