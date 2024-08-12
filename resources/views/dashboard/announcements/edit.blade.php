<x-dashboard-layout title="Edit Pengumuman">
    <x-slot name="header">
        Edit Pengumuman
    </x-slot>

    <div class="card p-4">
        <form method="post" action="{{ route('dashboard.announcements.update', $pengumuman->slug) }}" enctype="multipart/form-data">
            @csrf
            @method('put')

            <div class="mb-3">
                <label class="form-label" for="title">Judul Pengumuman <span style="font-size:14px;color:red">*</span></label>
                <input type="text" class="form-control {{ $errors->get('title') ? 'border-danger' : '' }}" id="title" name="title" placeholder="Nama Kategori" value="{{ old('title', $pengumuman->title) }}" autocomplete="title" autofocus required />
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="content">Isi Konten Pengumuman <span style="font-size:14px;color:red">*</span></label>
                <input id="content" type="hidden" name="content" value="{{ old('content', $pengumuman->content) }}">
                <trix-editor input="content"></trix-editor>
                <x-input-error class="mt-2" :messages="$errors->get('content')" />
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('dashboard.announcements.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</x-dashboard-layout>
