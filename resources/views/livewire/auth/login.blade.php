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
                        <h3 class="mb-3 fw-bold">Login SiJambu</h3>
                        @if (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form wire:submit.prevent='login'>
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
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="submit" class="btn btn-primary fw-bold">Login</button>
                                <a href="{{ route('register') }}" class="text-decoration-none">Belum punya akun? Daftar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
