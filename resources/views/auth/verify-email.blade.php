<x-auth-layout title="Verifikasi Email">
    <section class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card verify-email-card">
            <div class="card-body">
                <div class="app-brand justify-content-center fs-2 mb-3">
                    ADMIN PTIK
                </div>
                <div class="mb-2 text-center">
                    Terimakasih telah mendaftar! Sebelum memulai, bisakah anda memverifikasi alamat email anda dengan
                    mengklik link yang telah kami kirimkan ke email anda? Jika anda tidak menerima email, kami akan
                    dengan senang hati mengirimkan yang lain.
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-2 text-center fw-medium text-success">
                        Link verifikasi baru telah dikirim ke alamat email yang anda gunakan saat registrasi.
                    </div>
                @endif

                <div class="mt-3 d-flex align-items-center justify-content-between">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <div>
                            <button type="submit" class="btn btn-primary">
                                Kirim Email Verifikasi
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="btn btn-secondary">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-auth-layout>
