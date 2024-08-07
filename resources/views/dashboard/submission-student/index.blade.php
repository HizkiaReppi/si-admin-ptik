<x-dashboard-layout title="Manajemen Pengajuan">
    <x-slot name="header">
        Manajemen Pengajuan
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Manajemen Pengajuan Surat</h5>
            <div class="dropdown">
                <button class="btn btn-primary me-4 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Ajukan Surat
                </button>
                <ul class="dropdown-menu">
                    @foreach ($categories as $category)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('dashboard.submission.student.create', $category->slug) }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th class="text-center">Tipe Pengajuan</th>
                        <th class="text-center">Waktu Pengajuan</th>
                        <th class="text-center">Terakhir Diubah</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($submissions as $submission)
                        <tr>
                            <td class="text-md-center">{{ $submission->category->name }}</td>
                            <td class="text-center">{{ $submission->created_at->diffForHumans() }}</td>
                            <td class="text-center">{{ $submission->updated_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <span
                                    class="badge text-bg-{{ parseSubmissionBadgeClassNameStatus($submission->status) }}">
                                    {{ parseSubmissionStatus($submission->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($submission->status !== 'submitted')
                                    <a class="dropdown-item"
                                        href="{{ route('dashboard.submission.student.detail', [$submission->category->slug, $submission->id]) }}">
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
                                                href="{{ route('dashboard.submission.student.show', $submission->id) }}">
                                                <i class="bx bxs-user-detail me-1"></i> Detail
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.submission.student.edit', [$submission->category->slug, $submission->id]) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
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
