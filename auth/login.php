<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Geprek Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fff8e1 0%, #ffffff 50%, #f8f9fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 204, 0, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            top: -200px;
            right: -200px;
            z-index: 0;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 204, 0, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -100px;
            left: -100px;
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid rgba(255, 204, 0, 0.1);
        }

        .login-header {
            background: linear-gradient(135deg, #FFCC00 0%, #e6b800 100%);
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .logo-container {
            width: 80px;
            height: 80px;
            background: #ffffff;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .logo-container i {
            font-size: 2.5rem;
            color: #FFCC00;
        }

        .login-header h3 {
            font-weight: 700;
            color: #212529;
            margin-bottom: 0.5rem;
            font-size: 1.75rem;
        }

        .login-header p {
            color: rgba(33, 37, 41, 0.7);
            margin-bottom: 0;
            font-size: 0.9375rem;
        }

        .login-body {
            padding: 2.5rem 2rem;
        }

        .alert-custom {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            border: none;
            background: #ffebee;
            color: #c62828;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-custom i {
            font-size: 1.25rem;
        }

        .alert-custom .btn-close {
            padding: 0.5rem;
            opacity: 0.5;
        }

        .form-label-custom {
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.625rem;
            font-size: 0.9375rem;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.125rem;
            z-index: 2;
        }

        .form-control-custom {
            width: 100%;
            padding: 0.875rem 1.25rem 0.875rem 3.25rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 0.9375rem;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #FFCC00;
            box-shadow: 0 0 0 4px rgba(255, 204, 0, 0.1);
        }

        .form-control-custom::placeholder {
            color: #adb5bd;
        }

        .password-toggle {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0.25rem;
            font-size: 1.125rem;
            z-index: 2;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #FFCC00;
        }

        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #FFCC00 0%, #e6b800 100%);
            border: none;
            border-radius: 12px;
            color: #212529;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 204, 0, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e0e0e0;
        }

        .login-footer p {
            color: #6c757d;
            font-size: 0.875rem;
            margin: 0;
        }

        .login-footer a {
            color: #FFCC00;
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        /* Loading State */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #212529;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-header {
                padding: 2rem 1.5rem;
            }

            .login-header h3 {
                font-size: 1.5rem;
            }

            .logo-container {
                width: 70px;
                height: 70px;
            }

            .logo-container i {
                font-size: 2rem;
            }

            .login-body {
                padding: 2rem 1.5rem;
            }

            .form-control-custom {
                padding: 0.75rem 1rem 0.75rem 3rem;
            }

            .input-icon {
                left: 1rem;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <i class="bi bi-shop"></i>
                </div>
                <h3>Geprek Dashboard</h3>
                <p>Masuk untuk mengelola bisnis Anda</p>
            </div>

            <div class="login-body">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert-custom">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div style="flex: 1;">
                            <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
                    </div>
                <?php endif; ?>

                <form action="login_process.php" method="POST" id="loginForm">
                    <div class="mb-4">
                        <label for="nama" class="form-label-custom">Username</label>
                        <div class="input-group-custom">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" 
                                   class="form-control-custom" 
                                   id="nama" 
                                   name="nama" 
                                   placeholder="Masukkan username"
                                   required
                                   autocomplete="username">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label-custom">Password</label>
                        <div class="input-group-custom">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" 
                                   class="form-control-custom" 
                                   style="padding-right: 3.25rem;"
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan password"
                                   required
                                   autocomplete="current-password">
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn">
                        <span id="btnText">Masuk</span>
                    </button>
                </form>

                <div class="login-footer">
                    <p>© 2024 Geprek Dashboard. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        // Form Submit Loading State
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');
            
            btn.classList.add('loading');
            btnText.textContent = 'Memproses...';
            btn.style.position = 'relative';
        });

        // Auto focus username input
        window.addEventListener('load', function() {
            document.getElementById('nama').focus();
        });

        // Enter key on username moves to password
        document.getElementById('nama').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('password').focus();
            }
        });
    </script>
</body>

</html>