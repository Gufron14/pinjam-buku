<div>
    <div class="container py-4">
        <div class="main-body">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                @if ($newAvatar)
                                    <img src="{{ $newAvatar->temporaryUrl() }}" alt="{{ $name }}"
                                        class="rounded-circle p-1 bg-primary" width="110" height="110"
                                        style="object-fit: cover;">
                                @elseif($avatar && Storage::disk('public')->exists($avatar))
                                    <img src="{{ Storage::url($avatar) }}" alt="{{ $name }}"
                                        class="rounded-circle p-1 bg-primary" width="110" height="110"
                                        style="object-fit: cover;">
                                @else
                                    <img src="{{ asset('assets/img/user.png') }}" alt="{{ $name }}"
                                        class="rounded-circle p-1 bg-primary" width="110" height="110"
                                        style="object-fit: cover;">
                                @endif

                                <div class="mt-3">
                                    <h4>{{ $name }}</h4>
                                    <p class="text-secondary mb-1">{{ $email }}</p>
                                    <p class="text-muted font-size-sm">{{ $alamat }}</p>
                                    <div class="mt-2">
                                        <label for="avatar-upload" class="btn btn-primary btn-sm">
                                            Ubah Foto Profil
                                        </label>
                                        <input id="avatar-upload" type="file" wire:model="newAvatar" class="d-none">
                                    </div>
                                    @error('newAvatar')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                    @if ($newAvatar)
                                        <div class="mt-2">
                                            <span class="text-success">Foto baru dipilih. Klik "Simpan Perubahan" untuk
                                                menyimpan.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form wire:submit.prevent="updateProfile">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Nama Lengkap</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control" wire:model="name">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="email" class="form-control" wire:model="email">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">No. Telepon</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control" wire:model="no_telepon">
                                        @error('no_telepon')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Alamat</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control" wire:model="alamat">
                                        @error('alamat')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan
                                            Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
