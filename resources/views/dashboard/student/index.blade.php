<x-dashboard-layout title="Manajemen Mahasiswa">
    <x-slot name="header">
        Manajemen Mahasiswa
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Mahasiswa</h5>
            <a href="{{ route('dashboard.student.create') }}" class="btn btn-primary me-4">Tambah Mahasiswa</a>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table-student">
                <thead>
                    <tr>
                        <th class="text-center">Nama</th>
                        <th class="text-center">NIM</th>
                        <th class="text-center">Angkatan</th>
                        <th class="text-center">Dosen Pembimbing 1</th>
                        <th class="text-center">Dosen Pembimbing 2</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0"></tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table-student').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.student.index') }}',
                columns: [
                    { data: 'fullname', name: 'fullname' },
                    { data: 'nim', name: 'nim', className: 'text-center' },
                    { data: 'Angkatan', name: 'Angkatan', className: 'text-center', searchable: false },
                    { data: 'Dosen Pembimbing I', name: 'Dosen Pembimbing I' },
                    { data: 'Dosen Pembimbing II', name: 'Dosen Pembimbing II' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ]
            });
        });
    </script>
</x-dashboard-layout>
