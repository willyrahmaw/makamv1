<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Digitalisasi Makam</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
                <div class="login-header">
                    <div class="icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h4>Login Administrator</h4>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
            
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" placeholder="admin@makam.com" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       placeholder="••••••••" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Pertanyaan Keamanan</label>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-semibold" id="captcha-question">
                        {{ ($captchaQuestion ?? null) ? $captchaQuestion . ' = ?' : 'Berapa ' . (session('admin_login_captcha_answer') ? 'hasil penjumlahan di atas' : 'hasil penjumlahan?') }}
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-refresh-captcha">
                        <i class="bi bi-arrow-clockwise me-1"></i>Ganti soal
                    </button>
                </div>
                <input type="number" name="captcha_answer" class="form-control @error('captcha_answer') is-invalid @enderror"
                       placeholder="Jawaban Anda" required>
                @error('captcha_answer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
            </button>
            </form>

            <div class="back-link">
                <a href="{{ route('home') }}"><i class="bi bi-arrow-left me-1"></i> Kembali ke Website</a>
            </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const btn = document.getElementById('btn-refresh-captcha');
            const label = document.getElementById('captcha-question');
            if (!btn || !label) return;

            btn.addEventListener('click', function () {
                btn.disabled = true;
                fetch("{{ route('admin.captcha.refresh') }}", {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.question) {
                            label.textContent = data.question;
                        }
                    })
                    .catch(() => {})
                    .finally(() => {
                        btn.disabled = false;
                    });
            });
        })();
    </script>
</body>
</html>
