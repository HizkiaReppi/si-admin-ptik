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
                        <span class="badge text-bg-{{ $submission->parseSubmissionBadgeClassNameStatus }}">
                            {{ $submission->parseSubmissionStatus }}
                        </span>
                    </p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="category" class="form-label">Waktu Pengajuan</label>
                    <p class="border p-2 rounded m-0">
                        {{ $submission->created_at . ' (' . $submission->created_at->diffForHumans() . ')' }}
                    </p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="category" class="form-label">Terakhir Diubah</label>
                    <p class="border p-2 rounded m-0">
                        {{ $submission->updated_at . ' (' . $submission->updated_at->diffForHumans() . ')' }}
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
                                <a href="{{ route('dashboard.submission.view', $file->id) }}" class="btn btn-secondary">Lihat File</a>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($submission->status === 'done' && $submission->file_result)
                    <div class="mt-3 col-md-12">
                        <label for="fileResult" class="form-label">File Hasil Surat</label><br />
                        <a href="{{ route('dashboard.submission.view', [$submission->id, 'type' => 'file-result']) }}" class="btn btn-success">Lihat File</a>
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
                    <img src="/{{ $submission->student->user->photoFile }}" alt="{{ $submission->student->fullname }}"
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
                <div class="mb-3 col-md-6">
                    <label for="alamat" class="form-label">Status Verifikasi Email</label>
                    <p class="border p-2 rounded m-0">
                        @if ($submission->student->user->hasVerifiedEmail())
                            <span class="badge text-bg-primary">Terverifikasi</span>
                        @else
                            <span class="badge text-bg-danger">Tidak Terverifikasi</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="last-activity" class="form-label">Terakhir Dilihat</label>
                    <p class="border p-2 rounded m-0">
                        @if ($submission->student->user->isOnline())
                            <span class="badge text-bg-primary">Online</span>
                        @else
                            <span class="badge text-bg-secondary">{{ $submission->student->user->lastActivityAgo() }}</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="jabatan" class="form-label">Alamat</label>
                    <p class="border p-2 rounded m-0">{{ $submission->student->address ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mb-4 ms-3" style="margin-top: -15px">
            <a href="{{ route('dashboard.submission.index') }}" class="btn btn-outline-secondary ms-2">Kembali</a>
            @php
                $statusess = ['rejected', 'canceled', 'expired'];
                $isStatusInArray = in_array($submission->status, $statusess);
                $isStatusDoneAndOld = $submission->status == 'done' && $submission->updated_at->lt(now()->subDays(7));
            @endphp
            @if ($isStatusInArray || $isStatusDoneAndOld)
                <a class="btn btn-danger" href="{{ route('dashboard.submission.destroy', $submission->id) }}"
                    data-confirm-delete="true">
                    Hapus Pengajuan
                </a>
            @endif
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Respon Pengajuan
            </button>
        </div>

    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Respon Pengajuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('dashboard.submission.update', $submission->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status"
                                class="form-control {{ $errors->get('status') ? 'border-danger' : '' }}">
                                @foreach ($statuses as $status => $label)
                                    <option value="{{ $status }}"
                                        {{ old('status', $submission->status) == $status ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="note" id="noteLabel">Catatan <span
                                    id="noteOptional">(Opsional)</span></label>
                            <textarea class="form-control {{ $errors->get('note') ? 'border-danger' : '' }}" id="note" name="note"
                                placeholder="Catatan" autocomplete="note">{{ old('note', $submission->note) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('note')" />
                        </div>

                        <div class="mb-3" id="fileResultDiv">
                            <label for="fileResult" class="form-label">File Hasil Surat (Opsional)</label>
                            <input class="form-control" type="file" id="fileResult" name="file_result"
                                accept=".pdf, .docx, .doc" />
                            <x-input-error class="mt-2" :messages="$errors->get('file_result')" />
                            <div id="form-help" class="form-text">
                                <small>PDF, DOCX atau DOC (Max. 3 MB).</small>
                            </div>
                        </div>

                        <div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="submit">Simpan Respon</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const noteTextarea = document.getElementById('note');
            const noteOptional = document.getElementById('noteOptional');

            function updateNoteField() {
                const selectedStatus = statusSelect.value;
                const requiredStatuses = ['pending', 'rejected', 'canceled', 'expired'];

                if (requiredStatuses.includes(selectedStatus)) {
                    // noteTextarea.setAttribute('required', 'required');
                    noteOptional.style.display = 'none';
                } else {
                    // noteTextarea.removeAttribute('required');
                    noteOptional.style.display = 'inline';
                }

                if (selectedStatus === 'done') {
                    document.getElementById('fileResultDiv').style.display = 'block';
                } else {
                    document.getElementById('fileResultDiv').style.display = 'none';
                }
            }

            // Initial call to set the state based on the current value
            updateNoteField();

            // Add event listener to update the state when the value changes
            statusSelect.addEventListener('change', updateNoteField);
        });
    </script>
</x-dashboard-layout>
