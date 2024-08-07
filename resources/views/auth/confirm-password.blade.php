<x-auth-layout title="Konfirmasi Password">
    <section class="d-flex justify-content-center align-items-center" style="width: 100%; height: 100vh;">
        <div class="card">
            <div class="card-body">
                <h1 class="app-brand justify-content-center fs-3 mb-3">
                    Siapa Anda?
                </h1>
                <h4 class="mb-3 fs-5 text-center">Ini adalah area aman dari sistem informasi. <br> Harap konfirmasi password
                    anda sebelum melanjutkan.</h4>

                <form class="mb-3" method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password -->
                    <div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="******" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-2">
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('Konfirmasi') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-auth-layout>
