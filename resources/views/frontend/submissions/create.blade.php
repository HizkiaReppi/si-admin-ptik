<x-dashboard-layout title="Pengajuan Surat">
    <x-slot name="header">
        Pengajuan {{ $category->name }}
    </x-slot>

    <div class="card p-4">
        <form method="post" action="{{ route('dashboard.submission.store', $category->slug) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="category_name">Kategori</label>
                <p class="border p-2 rounded m-0">{{ $category->name }}</p>
            </div>

            <hr>

            <div id="requirement-fields">
                @if($category->requirements->count() > 0)
                    @foreach($category->requirements as $index => $requirement)
                        <div class="mb-3">
                            <label class="form-label" for="requirements[{{ $index }}]">{{ $requirement->name }} <span style="font-size:14px;color:red">*</span></label>
                            <input type="file" class="form-control {{ $errors->has("requirements.$index") ? 'border-danger' : '' }}" id="requirements[{{ $index }}]" name="requirements[{{ $index }}]" required>
                            @if ($requirement->file_path)
                            <a href="{{$requirement->file_path}}" class="mt-2 btn btn-info btn-sm" target="_blank">Contoh/Format Persyaratan</a>
                            @endif
                            @if ($errors->has("requirements.$index"))
                                <div class="text-danger mt-2">{{ $errors->first("requirements.$index") }}</div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p>Tidak ada persyaratan untuk kategori ini.</p>
                @endif
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Ajukan Surat</button>
                <a href="{{ route('dashboard.submission.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</x-dashboard-layout>
