<div>
    <div class="container col-xxl-8 px-4 py-5">
        <div class="card border-0 shadow-sm w-50 mx-auto">
            <div class="card-body p-5">
                <h3 class="mb-5 fw-bold">Login Jambu TaBaBa</h3>
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
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
                        <a href="{{ route('register') }}" class="text-decoration-none">Belum punya akun? Daftar</a>
                        <button type="submit" class="btn btn-success fw-bold w-50">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
