<x-dashboard-layout title="Manajemen Kategori">
    <x-slot name="header">
        Manajemen Kategori
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Kategori</h5>
            <a href="{{ route('dashboard.category.create') }}" class="btn btn-primary me-4">Tambah Kategori</a>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table-categories">
                <thead>
                    <tr>
                        {{-- <th class="text-center">No</th> --}}
                        <th class="text-center">Nama</th>
                        <th class="text-center">Persyaratan</th>
                        <th class="text-center">Total <br> Surat</th>
                        <th class="text-center">Surat <br> Selesai</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0"></tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table-categories').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.category.index') }}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'requirements', name: 'requirements', className: 'text-center', orderable: false, searchable: false, },
                    { data: 'total', name: 'total', className: 'text-center', orderable: false, searchable: false, },
                    { data: 'done', name: 'done', className: 'text-center', orderable: false, searchable: false, },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ]
            });
        });
    </script>
</x-dashboard-layout>
