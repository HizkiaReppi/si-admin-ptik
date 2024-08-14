<x-dashboard-layout title="Manajemen Dosen">
    <x-slot name="header">
        Manajemen Dosen
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Dosen</h5>
            <a href="{{ route('dashboard.lecturer.create') }}" class="btn btn-primary me-4">Tambah Dosen</a>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table-lecturer">
                <thead>
                    <tr>
                        <th class="text-center">Nama</th>
                        <th class="text-center">NIDN</th>
                        <th class="text-center">NIP</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0"></tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table-lecturer').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.lecturer.index') }}',
                columns: [
                    { data: 'fullname', name: 'fullname' },
                    { data: 'nidn', name: 'nidn', className: 'text-center' },
                    { data: 'nip', name: 'nip', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ]
            });
        });
    </script>
</x-dashboard-layout>
