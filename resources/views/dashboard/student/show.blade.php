<x-dashboard-layout title="Manajemen Mahasiswa">
    <x-slot name="header">
        Manajemen Mahasiswa
    </x-slot>

    <div class="card mb-4">
        <h5 class="card-header">Detail Mahasiswa</h5>
        <div class="card-body" style="margin-bottom: -20px">
            <div class="d-flex flex-column align-items-start gap-4">
                <label for="foto" class="form-label" style="margin-bottom: -10px">Foto</label>
                @if ($mahasiswa->user->photo == null)
                    <div class="border p-5 rounded" style="margin-bottom: -15px">Tidak Ada Foto</div>
                @else
                    <img src="/{{ $mahasiswa->user->photoFile }}"
                        alt="{{ $mahasiswa->fullname }}" class="d-block rounded" style="width: 250px" id="foto" />
                @endif
            </div>
        </div>
        <div class="card-body pb-2">
            <div class="row">
                <div class="mb-3 col-md-12">
                    <label for="fullname" class="form-label">Nama Lengkap</label>
                    <p class="border p-2 rounded m-0">{{ $mahasiswa->fullname }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="nim" class="form-label">NIM</label>
                    <p class="border p-2 rounded m-0">{{ $mahasiswa->formattedNIM }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="judu-skripsi" class="form-label">Semester</label>
                    <p class="border p-2 rounded m-0">{{ $mahasiswa->currentSemester }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="angkatan" class="form-label">Angkatan</label>
                    <p class="border p-2 rounded m-0">{{ $mahasiswa->batch }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="konsentrasi" class="form-label">Konsentrasi</label>
                    <p class="border p-2 rounded m-0">{{ $mahasiswa->concentration }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="dosen-pembimbing-1" class="form-label">Dosen Pembimbing I</label>
                    <p class="border p-2 rounded m-0">{{ $mahasiswa->firstSupervisorFullname }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="dosen-pembimbing-2" class="form-label">Dosen Pembimbing II</label>
                    <p class="border p-2 rounded m-0">{{ $mahasiswa->secondSupervisorFullname }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <p class="border p-2 rounded m-0">{{ $mahasiswa->user->email }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="no-hp" class="form-label">Nomor HP</label>
                    <p class="border p-2 rounded m-0">{{ $mahasiswa->phone_number ?? '-' }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="alamat" class="form-label">Status Verifikasi Email</label>
                    <p class="border p-2 rounded m-0">
                        @if ($mahasiswa->user->hasVerifiedEmail())
                            <span class="badge text-bg-primary">Terverifikasi</span>
                        @else
                            <span class="badge text-bg-danger">Tidak Terverifikasi</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="last-activity" class="form-label">Terakhir Dilihat</label>
                    <p class="border p-2 rounded m-0">
                        @if ($mahasiswa->user->isOnline())
                            <span class="badge text-bg-primary">Online</span>
                        @else
                            <span class="badge text-bg-secondary">{{ $mahasiswa->user->lastActivityAgo() }}</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="alamat" class="form-label">Alamat</label>
                    <p class="border p-2 rounded m-0">{{ $mahasiswa->address ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="d-flex mb-4 ms-3" style="margin-top: -15px">
            <a href="{{ route('dashboard.student.edit', $mahasiswa->id) }}" class="btn btn-primary ms-2">Edit Data</a>
            <a href="{{ route('dashboard.student.destroy', $mahasiswa->id) }}" class="btn btn-danger ms-2"
                data-confirm-delete="true">Hapus Data</a>
            <a href="{{ route('dashboard.student.index') }}" class="btn btn-outline-secondary ms-2">Kembali</a>
        </div>
    </div>
</x-dashboard-layout>
