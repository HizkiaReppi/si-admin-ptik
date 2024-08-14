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
            <table class="table" id="table-submission">
                <thead>
                    <tr>
                        <th class="text-center">Waktu Pengajuan</th>
                        <th class="text-center">Terakhir Diubah</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0"></tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table-submission').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.submission.student.show', $category->slug) }}',
                columns: [
                    { data: 'created_at', name: 'created_at', className: 'text-center', orderable: false, searchable: false },
                    { data: 'updated_at', name: 'updated_at', className: 'text-center', orderable: false, searchable: false },
                    { data: 'status', name: 'status', className: 'text-center', searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ]
            });
        });
    </script>
</x-dashboard-layout>
