<div>
    <div class="container col-xxl-8 px-4 py-5">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
            <div class="col-10 col-sm-8 col-lg-6">
                <img src="https://rencanamu.id/assets/file_uploaded/editor/1490954042-o-reading-.jpg"
                    class="d-block mx-lg-auto img-fluid" alt="baca buku" loading="lazy">
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body p-4">
                        <h3 class="mb-3 fw-bold">Daftar SiJambu</h3>
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form wire:submit.prevent='register'>                        
                            <div class="mb-3">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name"
                                    placeholder="Masukkan Nama Lengkap">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="form-control @error('alamat') is-invalid @enderror" wire:model="alamat"
                                    placeholder="Masukkan Alamat Lengkap">
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="no_telepon">Nomor WhatsApp</label>
                                <input type="number" class="form-control @error('no_telepon') is-invalid @enderror" wire:model="no_telepon"
                                    placeholder="Masukkan Nomor WhatsApp">
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email"
                                    placeholder="Masukkan email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" wire:model="password" placeholder="********">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" class="form-control" wire:model="password_confirmation" placeholder="********">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary fw-bold">Daftar</button>
                                <a href="{{ route('login') }}" class="text-decoration-none">Sudah punya akun? Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
