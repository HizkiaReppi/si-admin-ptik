<x-dashboard-layout title="Manajemen Administrator">
    <x-slot name="header">
        Manajemen Administrator
    </x-slot>

    <div class="card mb-4">
        <h5 class="card-header">Detail Administrator</h5>
        <div class="card-body" style="margin-bottom: -20px">
            <div class="d-flex flex-column align-items-start gap-4">
                <label for="foto" class="form-label" style="margin-bottom: -10px">Foto</label>
                @if ($administrator->photo == null)
                    <div class="border p-5 rounded" style="margin-bottom: -15px">Tidak Ada Foto</div>
                @else
                    <img src="/{{ $administrator->photoFile }}"
                        alt="{{ $administrator->name }}" class="d-block rounded" style="width: 250px" id="foto" />
                @endif
            </div>
        </div>
        <div class="card-body pb-2">
            <div class="row">
                <div class="mb-3 col-md-12">
                    <label for="fullname" class="form-label">Nama Lengkap</label>
                    <p class="border p-2 rounded m-0">{{ $administrator->name }}</p>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="username" class="form-label">Username</label>
                    <p class="border p-2 rounded m-0">{{ $administrator->username }}</p>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="email" class="form-label">Email</label>
                    <p class="border p-2 rounded m-0">{{ $administrator->email }}</p>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="last-activity" class="form-label">Terakhir Dilihat</label>
                    <p class="border p-2 rounded m-0">
                        @if ($administrator->isOnline())
                            <span class="badge text-bg-primary">Online</span>
                        @else
                            <span class="badge text-bg-secondary">{{ $administrator->lastActivityAgo() }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="d-flex mt-1 mb-4 ms-3" style="margin-top: -15px">
            @if($administrator->id == auth()->user()->id || auth()->user()->role == 'super-admin')
                <a href="{{ route('dashboard.administrator.edit', $administrator->id) }}" class="btn btn-primary ms-2">Edit Data</a>
            @endif
            @can('super-admin')
                <a href="{{ route('dashboard.administrator.destroy', $administrator->id) }}" class="btn btn-danger ms-2"
                    data-confirm-delete="true">Hapus Data</a>
            @endcan
            <a href="{{ route('dashboard.administrator.index') }}" class="btn btn-outline-secondary ms-2">Kembali</a>
        </div>
    </div>
</x-dashboard-layout>
