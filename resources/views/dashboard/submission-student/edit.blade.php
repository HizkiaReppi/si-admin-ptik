<x-dashboard-layout title="Pengajuan Surat">
    <x-slot name="header">
        Edit Pengajuan {{ $category->name }}
        <x-slot name="header_subtitle">
            Silakan unggah ulang berkas yang ingin diubah. Dan pastikan berkas yang diunggah sesuai dengan format yang telah ditentukan. <br>
            <strong>Perhatian:</strong> Tidak perlu mengunggah ulang berkas yang tidak ingin diubah.
        </x-slot>
    </x-slot>

    <div class="card p-4">
        <form method="post" action="{{ route('dashboard.submission.student.update', [$category->slug, $submission->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label class="form-label" for="category_name">Kategori</label>
                <p class="border p-2 rounded m-0">{{ $category->name }}</p>
            </div>

            <hr>

            <div id="requirement-fields">
                @if($category->requirements->count() > 0)
                    @foreach($category->requirements as $index => $requirement)
                        <div class="mb-3">
                            <label class="form-label" for="requirements[{{ $index }}]">{{ $requirement->name }}</label>
                            <input type="file" class="form-control {{ $errors->has("requirements.$index") ? 'border-danger' : '' }}" id="requirements[{{ $index }}]" name="requirements[{{ $index }}]">
                            @if ($requirement->file_path)
                            <a href="{{$requirement->file_path}}" class="mt-2 btn btn-info btn-sm" target="_blank">Contoh/Format Persyaratan</a>
                            @endif
                            @if ($category->submissions->count() > 0)
                                @foreach ($category->submissions as $submission)
                                    @foreach ($submission->files as $file)
                                        @php
                                            $fileParts = explode('_', basename($file->file_path));
                                            $categoryNameParts = explode(' ', $category->name);
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
                                        @if ($requirementName == $requirement->name)
                                            <a href="{{ $file->file_path }}" class="mt-2 btn btn-secondary btn-sm" download>File Saat Ini</a>
                                        @endif
                                    @endforeach
                                @endforeach

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
                <a href="{{ route('dashboard.submission.student.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
                <button type="submit" class="btn btn-primary">Edit Pengajuan Surat</button>
            </div>
        </form>
    </div>
</x-dashboard-layout>
