<x-dashboard-layout title="Manajemen Pengajuan">
    <x-slot name="header">
        Manajemen Pengajuan
    </x-slot>

    <div class="card mb-4">
        <h5 class="card-header">Detail Pengajuan</h5>
        <div class="card-body pb-2">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="category" class="form-label">Tipe Pengajuan</label>
                    <p class="border p-2 rounded m-0">{{ $submission->category->name }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">Status Pengajuan</label>
                    <p class="border p-2 rounded m-0">
                        <span class="badge text-bg-{{parseSubmissionBadgeClassNameStatus($submission->status)}}">
                            {{ parseSubmissionStatus($submission->status) }}
                        </span>
                    </p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="category" class="form-label">Waktu Pengajuan</label>
                    <p class="border p-2 rounded m-0">
                        {{ $submission->created_at . " (" . $submission->created_at->diffForHumans() . ")" }}
                    </p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="category" class="form-label">Terakhir Diubah</label>
                    <p class="border p-2 rounded m-0">
                        {{ $submission->updated_at . " (" . $submission->updated_at->diffForHumans() . ")" }}
                    </p>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="note" class="form-label">Catatan Dari Admin</label>
                    <p class="border p-2 rounded m-0">{{ $submission->note ?? '-' }}</p>
                </div>

                <div class="mt-2">
                    <h6 class="form-label">File Persyaratan Yang Diunggah</h6>
                    <div class="border p-2 rounded m-0 row">
                        @foreach ($submission->files as $file)
                            @php
                                $fileParts = explode('_', basename($file->file_path));
                                $categoryNameParts = explode(' ', $submission->category->name);
                                $requirementName = '';

                                foreach ($fileParts as $index => $part) {
                                    if (in_array($part, $categoryNameParts)) {
                                        $requirementName = implode(
                                            ' ',
                                            array_slice($fileParts, $index + count($categoryNameParts)),
                                        );
                                        break;
                                    }
                                }

                                $requirementName = str_replace(['.pdf', '.docx', '.doc'], '', $requirementName);
                            @endphp
                            <div class="mb-3 col-6 col-md-4">
                                <label for="requirement_{{ $loop->index }}"
                                    class="form-label">{{ $requirementName }}</label><br>
                                <a href="{{ asset($file->file_path) }}" class="btn btn-secondary" download>Download</a>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($submission->status === 'done' && $submission->file_result)
                    <div class="mt-3 col-md-12">
                        <label for="fileResult" class="form-label">File Hasil Surat</label><br />
                        <a href="{{ asset($submission->file_result) }}" class="btn btn-success" download>Download</a>
                    </div>
                @endif
            </div>
        </div>
        <hr>
        <h5 class="card-header">Detail Mahasiswa</h5>
        <div class="card-body" style="margin-bottom: -20px">
            <div class="d-flex flex-column align-items-start gap-4">
                <label for="foto" class="form-label" style="margin-bottom: -10px">Foto</label>
                @if ($submission->student->user->photo == null)
                    <div class="border p-5 rounded" style="margin-bottom: -15px">Tidak Ada Foto</div>
                @else
                    <img src="{{ $submission->student->user->photoFile }}" alt="{{ $submission->student->fullname }}"
                        class="d-block rounded" style="width: 250px" id="foto" />
                @endif
            </div>
        </div>
        <div class="card-body pb-2">
            <div class="row">
                <div class="mb-3 col-md-12">
                    <label for="fullname" class="form-label">Nama Lengkap</label>
                    <p class="border p-2 rounded m-0">{{ $submission->student->fullname }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="nim" class="form-label">NIM</label>
                    <p class="border p-2 rounded m-0">{{ $submission->student->formattedNIM }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="judu-skripsi" class="form-label">Semester</label>
                    <p class="border p-2 rounded m-0">{{ $submission->student->currentSemester }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="angkatan" class="form-label">Angkatan</label>
                    <p class="border p-2 rounded m-0">{{ $submission->student->batch }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="konsentrasi" class="form-label">Konsentrasi</label>
                    <p class="border p-2 rounded m-0">{{ $submission->student->concentration }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <p class="border p-2 rounded m-0">
                        <a
                            href="mailto:{{ $submission->student->user->email }}">{{ $submission->student->user->email }}</a>
                    </p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="no-hp" class="form-label">Nomor HP</label>
                    <p class="border p-2 rounded m-0">
                        {{ $submission->student->phone_number ?? '-' }}
                    </p>
                    <div class="d-flex align-items-start mt-1 gap-2">
                        @php
                            $phone_number = preg_replace('/^0/', '62', $submission->student->phone_number);
                        @endphp
                        @if ($submission->student->phone_number)
                            <a href="tel:+{{ $phone_number }}"
                                class="d-flex btn-sm align-items-center btn btn-outline-secondary">
                                <i class="fa-solid fa-phone me-2"></i> Telepon
                            </a>
                            <a href="https://wa.me/{{ $phone_number }}"
                                class="d-flex align-items-center btn btn-sm btn-outline-secondary">
                                <i class="fa fa-whatsapp me-2"></i> Whatsapp
                            </a>
                        @endif
                    </div>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="dosen-pembimbing-1" class="form-label">Dosen Pembimbing I</label>
                    <p class="border p-2 rounded m-0">{{ $submission->student->firstSupervisorFullname }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="dosen-pembimbing-2" class="form-label">Dosen Pembimbing II</label>
                    <p class="border p-2 rounded m-0">{{ $submission->student->secondSupervisorFullname }}</p>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="jabatan" class="form-label">Alamat</label>
                    <p class="border p-2 rounded m-0">{{ $submission->student->address ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mb-4 ms-3" style="margin-top: -15px">
            <a href="{{ route('dashboard.submission.index') }}" class="btn btn-outline-secondary ms-2">Kembali</a>
            @if ($submission->status == 'submitted')
                <a class="btn btn-danger" href="{{ route('dashboard.submission.student.destroy', $submission->id) }}" data-confirm-delete="true">
                    Hapus Pengajuan
                </a>
                <a href="{{ route('dashboard.submission.student.edit', [$category->slug, $submission->id]) }}" class="btn btn-primary">Edit Pengajuan</a>
            @endif
        </div>
    </div>
</x-dashboard-layout>
