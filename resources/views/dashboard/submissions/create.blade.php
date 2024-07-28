<x-dashboard-layout title="Pengajuan Surat">
    <x-slot name="header">
        Pengajuan Surat
    </x-slot>

    <div class="card p-4">
        <form method="post" action="{{ route('dashboard.submission.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="student_id" class="form-label">Nama Mahasiswa <span style="font-size:14px;color:red">*</span></label>
                <x-select :options="$students" key="fullname" placeholders="Nama Mahasiswa" id="student_id"
                    name="student_id" required />
                <x-input-error class="mt-2" :messages="$errors->get('student_id')" />
            </div>

            <div class="mb-3">
                <label class="form-label" for="category_id">Tipe Pengajuan <span style="font-size:14px;color:red">*</span></label>
                <x-select :options="$categories" key="name" placeholders="Pilih Tipe Pengajuan" id="category_id"
                    name="category_id" required />
                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
            </div>

            <div id="requirement-fields">
                <!-- Persyaratan akan dimuat di sini dengan JavaScript -->
            </div>

            <div>
                <button type="submit" class="btn btn-primary" id="submit">Ajukan Surat</button>
                <a href="{{ route('dashboard.submission.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categories = @json($categories);
            const errors = @json($errors->toArray());
            const oldRequirements = @json(old('requirements', []));

            const requirementFieldsContainer = document.getElementById('requirement-fields');
            const categorySelect = document.getElementById('category_id');

            categorySelect.addEventListener('change', function () {
                const selectedCategory = categories.find(category => category.id == this.value);
                requirementFieldsContainer.innerHTML = '';

                if(!selectedCategory) {
                    requirementFieldsContainer.innerHTML = '<p>Silahkan memilih tipe surat yang ingin diajukan.</p>';
                }

                if (selectedCategory && selectedCategory.requirements.length > 0) {
                    selectedCategory.requirements.forEach((requirement, index) => {
                        const requirementField = `
                            <div class="mb-3">
                                <label class="form-label" for="requirements[${index}]">${requirement.name} <span style="font-size:14px;color:red">*</span></label>
                                <input type="file" class="form-control ${errors[`requirements.${index}`] ? 'border-danger' : ''}" id="requirements[${index}]" name="requirements[${index}]" required>
                                ${requirement.file_path ? `<a href="${requirement.file_path}" class="mt-2 btn btn-info btn-sm" target="_blank">Contoh/Format Persyaratan</a>` : ''}
                                ${errors[`requirements.${index}`] ? `<div class="text-danger mt-2">${errors[`requirements.${index}`][0]}</div>` : ''}
                            </div>
                        `;
                        requirementFieldsContainer.insertAdjacentHTML('beforeend', requirementField);
                    });
                } else {
                    requirementFieldsContainer.innerHTML = '<p>Tidak ada persyaratan untuk kategori ini.</p>';
                }

                const btnSubmit = document.getElementById('submit');
                btnSubmit.disabled = !selectedCategory;
            });

            // Trigger change event to load requirements for the selected category on page load
            categorySelect.dispatchEvent(new Event('change'));

            // Re-populate the old input values if available (for example, after validation errors)
            if (oldRequirements.length > 0 && categorySelect.value) {
                categorySelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-dashboard-layout>
