<x-auth-layout title="Register">
    <!-- Register -->
    <section class="d-flex justify-content-center align-items-center" style="width: 100%;">
        <div class="card">
            <div class="card-body">
                <!-- Logo -->
                <div class="app-brand justify-content-center fs-2 mb-3">
                    <a href="/" class="app-brand-link">
                        ADMIN PTIK
                    </a>
                </div>
                <h4 class="mb-3 fs-4 text-center">Selamat Datang di SI Admin PTIK</h4>

                <form class="mb-3 mt-1 row" method="post" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="fullname">Nama Lengkap <span
                                style="font-size:14px;color:red">*</span></label>
                        <input type="text" class="form-control {{ $errors->get('fullname') ? 'border-danger' : '' }}"
                            id="fullname" name="fullname" placeholder="Nama Lengkap" value="{{ old('fullname') }}"
                            autocomplete="name" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('fullname')" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="nim">NIM <span style="font-size:14px;color:red">*</span></label>
                        <input type="number" class="form-control {{ $errors->get('nim') ? 'border-danger' : '' }}"
                            id="nim" name="nim" placeholder="NIM" value="{{ old('nim') }}" autocomplete="nim"
                            required />
                        <x-input-error class="mt-2" :messages="$errors->get('nim')" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="email">Email <span style="font-size:14px;color:red">*</span></label>
                        <input type="email" class="form-control {{ $errors->get('email') ? 'border-danger' : '' }}"
                            id="email" name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="email"
                            required />
                        <div id="form-email-help" class="form-text"></div>
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="angkatan">Angkatan <span
                                style="font-size:14px;color:red">*</span></label>
                        <input type="number" class="form-control {{ $errors->get('angkatan') ? 'border-danger' : '' }}"
                            id="angkatan" min="2018" max="{{ date('Y') }}" name="angkatan" placeholder="Angkatan" value="{{ old('angkatan') }}"
                            autocomplete="year" required />
                        <x-input-error class="mt-2" :messages="$errors->get('angkatan')" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="konsentrasi" class="form-label">Konsentrasi <span
                                style="font-size:14px;color:red">*</span></label>
                        <select class="form-select {{ $errors->get('konsentrasi') ? 'border-danger' : '' }}" id="konsentrasi"
                            name="konsentrasi" aria-label="Konsentrasi" required>
                            <option selected value="choose">Pilih Konsentrasi</option>
                            @foreach ($concentrations as $concentration)
                                @if (old('konsentrasi') == $concentration)
                                    <option value="{{ $concentration }}" selected>{{ strtoupper($concentration) }}</option>
                                @else
                                    <option value="{{ $concentration }}">{{ strtoupper($concentration) }}</option>
                                @endif
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('konsentrasi')" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lecturer_id_1" class="form-label">Dosen Pembimbing Akademik <span
                                style="font-size:14px;color:red">*</span></label>
                        <x-select :options="$lecturers" key="fullname" placeholders="Pilih Dosen Pembimbing Akademik" id="lecturer_id_1"
                            name="lecturer_id_1" required />
                        <x-input-error class="mt-2" :messages="$errors->get('lecturer_id_1')" />
                    </div>
                    <div class="mb-3 col-md-6 form-password-toggle">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password"
                            placeholder="******" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6 form-password-toggle">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                                placeholder="******" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>
                    <div class="mb-1">
                        <button class="btn btn-primary d-grid w-100" type="submit">Register</button>
                    </div>
                </form>
                <p class="text-center">
                    <span>Sudah memiliki akun?</span>
                    <a href="{{ route('login') }}">
                        <span>Masuk sekarang!</span>
                    </a>
                </p>
            </div>
        </div>
    </section>
</x-auth-layout>
