<x-dashboard-layout title="Tambah Data Administrator">
    <x-slot name="header">
        Tambah Data Administrator
    </x-slot>

    <div class="card p-4">
        <form method="post" action="{{ route('dashboard.administrator.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="fullname">Nama Lengkap</label>
                <input type="text" class="form-control {{ $errors->get('fullname') ? 'border-danger' : '' }}"
                    id="fullname" name="fullname" placeholder="Nama Lengkap" value="{{ old('fullname') }}"
                    autocomplete="name" autofocus required />
                <x-input-error class="mt-2" :messages="$errors->get('fullname')" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input type="text" class="form-control {{ $errors->get('username') ? 'border-danger' : '' }}"
                    id="username" name="username" placeholder="Username" value="{{ old('username') }}"
                    autocomplete="username" required />
                <x-input-error class="mt-2" :messages="$errors->get('username')" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control {{ $errors->get('email') ? 'border-danger' : '' }}"
                    id="email" name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="email"
                    required />
                <div id="form-email-help" class="form-text"></div>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
            <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password" placeholder="******" />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
                <div id="password-suggestions" class="mt-2 text-danger"></div>
                <div class="mt-2 d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-secondary" id="generatePassword">Generate Password</button>
                    <button type="button" class="btn btn-secondary" id="copyPassword">Copy to Clipboard</button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <div class="input-group input-group-merge">
                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                        placeholder="******" />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto (Opsional)</label>
                <img class="img-preview img-thumbnail rounded" style="width: 300px; height: auto;">
                <input class="form-control" type="file" id="foto" name="foto" accept=".png, .jpg, .jpeg" />
                <x-input-error class="mt-2" :messages="$errors->get('foto')" />
                <div id="form-help" class="form-text">
                    <small>PNG, JPG atau JPEG (Max. 2 MB).</small>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Tambah Data</button>
                <a href="{{ route('dashboard.administrator.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const generateButton = document.getElementById('generatePassword');
            const copyButton = document.getElementById('copyPassword');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const passwordSuggestions = document.getElementById('password-suggestions');

            function generatePassword() {
                const length = 20;
                const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
                let password = "";
                for (let i = 0, n = charset.length; i < length; ++i) {
                    password += charset.charAt(Math.floor(Math.random() * n));
                }
                return password;
            }

            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    passwordSuggestions.innerHTML =
                        '<span class="text-success">Password berhasil disalin ke clipboard!</span>';
                }, function(err) {
                    passwordSuggestions.innerHTML = 'Tidak bisa menyalin ke clipboard: ', err;
                });
            }

            function validatePassword(password) {
                const minLength = 8;
                const hasUpperCase = /[A-Z]/.test(password);
                const hasLowerCase = /[a-z]/.test(password);
                const hasNumber = /\d/.test(password);
                const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

                let suggestions = [];
                if (!hasUpperCase) suggestions.push('<strong>1 huruf kapital</strong>');
                if (!hasLowerCase) suggestions.push('<strong>1 huruf kecil</strong>');
                if (!hasNumber) suggestions.push('<strong>1 angka</strong>');
                if (!hasSpecialChar) suggestions.push('<strong>1 karakter spesial</strong>');
                if (password.length < minLength) suggestions.push('<strong>minimal 8 karakter</strong>');

                return suggestions;
            }

            passwordInput.addEventListener('input', () => {
                const password = passwordInput.value;
                const suggestions = validatePassword(password);

                if (suggestions.length > 0) {
                    passwordSuggestions.innerHTML = 'Password harus memiliki ' + suggestions.join(', ') +
                        '.';
                } else {
                    passwordSuggestions.innerHTML = '<strong class="text-success">Password is strong!</strong>';
                }
            });

            generateButton.addEventListener('click', () => {
                const newPassword = generatePassword();
                passwordInput.value = newPassword;
                passwordConfirmationInput.value = newPassword;
                passwordSuggestions.innerHTML = '<strong class="text-success">Password is strong!</strong>';
            });

            copyButton.addEventListener('click', () => {
                const password = passwordInput.value;
                if (password) {
                    copyToClipboard(password);
                } else {
                    passwordSuggestions.innerHTML = 'Silahkan generate password terlebih dahulu.';
                }
            });
        });
    </script>
</x-dashboard-layout>
