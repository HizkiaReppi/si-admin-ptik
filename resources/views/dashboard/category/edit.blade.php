<x-dashboard-layout title="Edit Data Kategori">
    <x-slot name="header">
        Edit Data Kategori
    </x-slot>

    <div class="card p-4">
        <form method="post" action="{{ route('dashboard.category.update', $kategori->slug) }}" enctype="multipart/form-data">
            @csrf
            @method('put')

            <div class="mb-3">
                <label class="form-label" for="name">Nama Kategori <span style="font-size:14px;color:red">*</span></label>
                <input type="text" class="form-control {{ $errors->get('name') ? 'border-danger' : '' }}" id="name" name="name" placeholder="Nama Kategori" value="{{ old('name', $kategori->name) }}" autocomplete="name" autofocus required />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="requirements">Persyaratan Kategori</label>
                <div id="requirement-fields">
                    @if(old('requirements'))
                        @foreach(old('requirements') as $index => $requirement)
                            <div class="row mb-3 requirement-item" data-index="{{ $index }}">
                                <div class="col-md-6">
                                    <label class="form-label" for="requirements[{{ $index }}][name]">Nama Persyaratan {{ $index + 1 }} <span style="font-size:14px;color:red">*</span></label>
                                    <input type="text" class="form-control" name="requirements[{{ $index }}][name]" id="requirements[{{ $index }}][name]" placeholder="Nama Persyaratan" value="{{ $requirement['name'] }}" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="requirements[{{ $index }}][file]">Contoh/Format Persyaratan {{ $index + 1 }}</label>
                                    <input type="file" class="form-control" name="requirements[{{ $index }}][file]" id="requirements[{{ $index }}][file]" />
                                </div>
                                <div class="col-12 text-end mt-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-requirement">Hapus</button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach($kategori->requirements as $index => $requirement)
                            <div class="row mb-3 requirement-item" data-index="{{ $index }}">
                                <div class="col-md-6">
                                    <label class="form-label" for="requirements[{{ $index }}][name]">Nama Persyaratan {{ $index + 1 }} <span style="font-size:14px;color:red">*</span></label>
                                    <input type="text" class="form-control" name="requirements[{{ $index }}][name]" id="requirements[{{ $index }}][name]" placeholder="Nama Persyaratan" value="{{ old('requirements.' . $index . '.name', $requirement->name) }}" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="requirements[{{ $index }}][file]">Contoh/Format Persyaratan {{ $index + 1 }}</label>
                                    <input type="file" class="form-control" name="requirements[{{ $index }}][file]" id="requirements[{{ $index }}][file]" />
                                </div>
                                <div class="col-12 text-end mt-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-requirement">Hapus</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="btn btn-outline-secondary" id="add-requirement">Tambah Persyaratan</button>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('dashboard.category.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>

    <script>
        let removedIndexes = [];

        function updateRequirementIndexes() {
            const requirementItems = document.querySelectorAll('.requirement-item');
            requirementItems.forEach((item, index) => {
                const currentIndex = item.getAttribute('data-index');
                const nameInput = item.querySelector('input[name$="[name]"]');
                const fileInput = item.querySelector('input[name$="[file]"]');
                const removeButton = item.querySelector('.remove-requirement');

                nameInput.name = `requirements[${currentIndex}][name]`;
                nameInput.id = `requirements[${currentIndex}][name]`;
                fileInput.name = `requirements[${currentIndex}][file]`;
                fileInput.id = `requirements[${currentIndex}][file]`;

                const nameLabel = item.querySelector(`label[for="requirements[${currentIndex}][name]"]`);
                const fileLabel = item.querySelector(`label[for="requirements[${currentIndex}][file]"]`);
                if (nameLabel && fileLabel) {
                    nameLabel.innerHTML = `Nama Persyaratan ${index + 1} <span style="font-size:14px;color:red">*</span>`;
                    fileLabel.innerHTML = `Contoh/Format Persyaratan ${index + 1}`;
                }

                removeButton.style.display = requirementItems.length > 1 ? 'block' : 'none';
            });
        }

        document.getElementById('add-requirement').addEventListener('click', function () {
            const container = document.getElementById('requirement-fields');
            const newIndex = removedIndexes.length ? removedIndexes.shift() : container.children.length;

            const newField = document.createElement('div');
            newField.className = 'row mb-3 requirement-item';
            newField.setAttribute('data-index', newIndex);
            newField.innerHTML = `
                <div class="col-md-6">
                    <label class="form-label" for="requirements[${newIndex}][name]">Nama Persyaratan ${container.children.length + 1} <span style="font-size:14px;color:red">*</span></label>
                    <input type="text" class="form-control" name="requirements[${newIndex}][name]" id="requirements[${newIndex}][name]" placeholder="Nama Persyaratan" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="requirements[${newIndex}][file]">Contoh/Format Persyaratan ${container.children.length + 1}</label>
                    <input type="file" class="form-control" name="requirements[${newIndex}][file]" id="requirements[${newIndex}][file]" />
                </div>
                <div class="col-12 text-end mt-1">
                    <button type="button" class="btn btn-danger btn-sm remove-requirement">Hapus</button>
                </div>
            `;

            container.appendChild(newField);
            updateRequirementIndexes();
        });

        document.getElementById('requirement-fields').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-requirement')) {
                const requirementItem = e.target.closest('.requirement-item');
                const currentIndex = requirementItem.getAttribute('data-index');
                removedIndexes.push(parseInt(currentIndex));
                requirementItem.remove();
                updateRequirementIndexes();
            }
        });

        updateRequirementIndexes();
    </script>
</x-dashboard-layout>
