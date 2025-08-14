
    <div class="container">
        <h1>Verifikasi Email</h1>
        <p>Silakan cek email Anda dan klik link verifikasi untuk melanjutkan.</p>
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Kirim Ulang Email Verifikasi</button>
        </form>
    </div>