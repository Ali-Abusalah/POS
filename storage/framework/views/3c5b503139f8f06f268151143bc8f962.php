<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login - <?php echo e(config('app.name', 'POS')); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            padding: 1rem;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(0,0,128,0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(0,0,128,0.08) 0%, transparent 50%);
            pointer-events: none;
        }
        .bg-decoration {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .bg-decoration .circle {
            position: absolute;
            border: 1px solid rgba(0,0,128,0.15);
            border-radius: 50%;
        }
        .bg-decoration .circle:nth-child(1) { width: 400px; height: 400px; top: -100px; left: -100px; }
        .bg-decoration .circle:nth-child(2) { width: 500px; height: 500px; top: -150px; left: -150px; }
        .bg-decoration .circle:nth-child(3) { width: 300px; height: 300px; top: -50px; left: -50px; }
        .bg-decoration .circle:nth-child(4) { width: 400px; height: 400px; bottom: -100px; right: -100px; }
        .bg-decoration .circle:nth-child(5) { width: 500px; height: 500px; bottom: -150px; right: -150px; }
        .bg-decoration .circle:nth-child(6) { width: 300px; height: 300px; bottom: -50px; right: -50px; }
        .bg-decoration .line {
            position: absolute;
            background: rgba(0,0,128,0.1);
        }
        .bg-decoration .line:nth-child(7) { width: 1px; height: 100%; left: 15%; top: 0; }
        .bg-decoration .line:nth-child(8) { width: 1px; height: 100%; right: 15%; top: 0; }
        .bg-decoration .line:nth-child(9) { width: 100%; height: 1px; top: 30%; left: 0; }
        .bg-decoration .line:nth-child(10) { width: 100%; height: 1px; bottom: 30%; left: 0; }
        .bg-decoration .tech-circle {
            position: absolute;
            border: 1px solid rgba(0,0,128,0.12);
            border-radius: 50%;
        }
        .bg-decoration .tech-circle::before {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid rgba(0,0,128,0.08);
            border-radius: 50%;
        }
        .bg-decoration .tech-circle:nth-child(11) { width: 600px; height: 600px; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .bg-decoration .tech-circle:nth-child(11)::before { width: 450px; height: 450px; }
        .bg-decoration .tech-circle:nth-child(12) { width: 700px; height: 700px; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .bg-decoration .tech-circle:nth-child(12)::before { width: 550px; height: 550px; }

        .login-card {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 8px;
            padding: 2.5rem 3rem 2rem;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            position: relative;
            z-index: 100;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 1rem;
        }
        .login-logo img {
            height: 40px;
        }
        .login-logo .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: #000080;
            letter-spacing: 0.05em;
        }
        .login-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .login-divider::before,
        .login-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }
        .login-divider span {
            font-size: 0.875rem;
            color: #555555;
            font-weight: 500;
            white-space: nowrap;
        }
        .login-error {
            padding: 0.75rem 1rem;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 6px;
            font-size: 0.8125rem;
            margin-bottom: 1rem;
        }
        .input-group {
            position: relative;
            margin-bottom: 1rem;
        }
        .input-group .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #888888;
            display: flex;
            align-items: center;
            font-size: 0.875rem;
        }
        .input-group .input-field {
            width: 100%;
            padding: 0.875rem 0.875rem 0.875rem 2.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.875rem;
            color: #000080;
            background: #ffffff;
            outline: none;
            transition: border-color 0.2s;
        }
        .input-group .input-field::placeholder { color: #888888; }
        .input-group .input-field:focus { border-color: #000080; }
        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #555555;
            cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #000080;
            cursor: pointer;
        }
        .forgot-link {
            background: none;
            border: none;
            color: #000080;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
            text-decoration: none;
        }
        .forgot-link:hover { text-decoration: underline; }
        .login-btn {
            width: 100%;
            padding: 0.75rem 1rem;
            background: transparent;
            color: #000080;
            border: 1.5px solid #000080;
            border-radius: 6px;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        .login-btn:hover { background: #000080; color: #ffffff; }
        .client-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }
        .client-divider::before,
        .client-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }
        .client-divider span {
            font-size: 0.8125rem;
            color: #888888;
            white-space: nowrap;
        }
        .customer-btn {
            width: 100%;
            padding: 0.75rem 1rem;
            background: transparent;
            color: #dc2626;
            border: 1.5px solid #fca5a5;
            border-radius: 6px;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            text-decoration: none;
        }
        .customer-btn:hover { background: #fef2f2; border-color: #dc2626; }
    </style>
</head>
<body>
    <div class="bg-decoration">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="tech-circle"></div>
        <div class="tech-circle"></div>
    </div>

    <div class="login-card">
        <div class="login-logo">
            <img src="<?php echo e(asset('logo.png')); ?>" alt="LOGO" onerror="this.style.display='none';this.nextElementSibling.style.display='inline';">
            <span class="logo-text" style="display:none;">LOGO</span>
        </div>

        <div class="login-divider">
            <span>Employee Login Panel</span>
        </div>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="login-error">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div><?php echo e($error); ?></div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="input-group">
                <span class="input-icon">
                    <i class="fa-regular fa-user"></i>
                </span>
                <input
                    type="email"
                    name="email"
                    value="<?php echo e(old('email')); ?>"
                    required
                    placeholder="Your Email"
                    class="input-field"
                    autocomplete="email"
                />
            </div>

            <div class="input-group">
                <span class="input-icon">
                    <i class="fa-solid fa-key"></i>
                </span>
                <input
                    type="password"
                    name="password"
                    required
                    placeholder="Your Password"
                    class="input-field"
                    autocomplete="current-password"
                />
            </div>

            <div class="login-options">
                <label class="remember-label">
                    <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?> />
                    Remember Me
                </label>
                <a href="#" class="forgot-link">Forgot Password ?</a>
            </div>

            <button type="submit" class="login-btn">
                <i class="fa-solid fa-lock"></i>
                Login
            </button>
        </form>

        <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            var form = this;
            var btn = document.querySelector('.login-btn');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Logging in...';
            btn.disabled = true;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200 || xhr.status === 302) {
                    window.location.href = '/pos';
                } else {
                    document.body.innerHTML = '<div style="padding:2rem;text-align:center;font-family:sans-serif;"><h2>Login Failed</h2><p>Email or password is incorrect.</p><a href="/login" style="color:#000080;">Try Again</a></div>';
                }
            };
            xhr.onerror = function() {
                btn.innerHTML = '<i class="fa-solid fa-lock"></i> Login';
                btn.disabled = false;
                alert('Network error. Check your connection.');
            };
            var data = new FormData(form);
            xhr.send(new URLSearchParams(data));
        });
        </script>

        <div class="client-divider">
            <span>Are you a client ?</span>
        </div>

        <a href="#" class="customer-btn">
            <i class="fa-regular fa-user"></i>
            Customer Login
        </a>
    </div>
</body>
</html>
<?php /**PATH C:\Users\ALI.A.SALAH\Desktop\pos\resources\views/auth/login.blade.php ENDPATH**/ ?>