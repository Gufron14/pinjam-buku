<div>
    <div class="container px-4 py-5">
        <div class="card shadow border-0">
            <div class="card-body p-5">
                <h3 class="mb-5 fw-bold">Daftar Jambu TaBaBa</h3>
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <form wire:submit.prevent='register'>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    wire:model="name" placeholder="Masukan Nama Lengkap">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="ttl" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('ttl') is-invalid @enderror"
                                    wire:model.lazy="ttl" placeholder="Masukan Tanggal Lahir" min="{{ date('Y-m-d', strtotime('-100 years')) }}"
                                    max="{{ date('Y-m-d', strtotime('-6 years')) }}">
                                @error('ttl')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                                    wire:model="alamat" placeholder="Masukan Alamat Lengkap">
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">Nomor WhatsApp</label>
                                <input type="number" class="form-control @error('no_telepon') is-invalid @enderror"
                                    wire:model="no_telepon" placeholder="Masukan Nomor WhatsApp">
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if($umur !== null && $umur >= 17)
                            <div class="mb-3">
                                <label for="ktp" class="form-label">Foto KTP</label>
                                <input type="file" class="form-control @error('ktp') is-invalid @enderror"
                                    wire:model="ktp" placeholder="Masukan email">
                                @error('ktp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    wire:model="email" placeholder="Masukan email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    wire:model="password" placeholder="********">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" wire:model="password_confirmation"
                                    placeholder="********">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <a href="{{ route('login') }}" class="text-decoration-none">Sudah punya akun? Login</a>
                                <button type="submit" class="btn btn-primary fw-bold  w-50">Daftar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>