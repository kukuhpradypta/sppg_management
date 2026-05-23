<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login | SPPG Monitoring System</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#00236f",
                    "primary-container": "#1e3a8a",
                    "on-primary": "#ffffff",
                    "on-surface": "#0b1c30",
                    "on-surface-variant": "#444651",
                    "surface": "#f8f9ff",
                    "surface-container-lowest": "#ffffff",
                    "outline": "#757682",
                    "outline-variant": "#c5c5d3",
                    "secondary-container": "#6cf8bb",
                    "on-secondary-container": "#00714d",
                    "error": "#ba1a1a",
                },
                fontFamily: {
                    'sans': ['Inter', 'sans-serif'],
                },
            },
        },
    }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
        .bg-pattern {
            background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-surface text-on-surface overflow-hidden">

<main class="flex min-h-screen w-full">
    <!-- Left Side: Professional Imagery -->
    <section class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative bg-primary overflow-hidden">
        <img class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay"
            alt="Industrial kitchen facility"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDL3Fw_IP-EaCjzs4QYW0WrlgTcJR6TT1JUQ8iJOq8l6C9DABkZ3OiJhD7GDtjgZdrEhv4Oou2fEn1uYLeN-_7nl5H3QYdSETCayCOiU6j9ct0HSXiuXFDm2tI8uKynVKpS_ED78qfie1eS9lasRXoJs_EoLFzNZQ2yBIwWyfwxSCOEhV0zX7KLEp29dxqkddv7aKwP4OrJx8vC_immWnTCVl89og3PxVbJMMQ7pnbW4N1Djsw9knIJW4aMLJY-JZunLxUIHFrlbVY"/>
        <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/40 to-transparent"></div>

        <!-- Logo Top Left -->
        <div class="absolute top-12 left-12 z-10 flex items-center gap-3">
            <div class="bg-secondary-container p-2 rounded-lg">
                <span class="material-symbols-outlined text-on-secondary-container" style="font-variation-settings: 'FILL' 1;">restaurant</span>
            </div>
            <span class="text-2xl text-on-primary font-bold tracking-tight">SPPG Monitoring</span>
        </div>

        <!-- Bottom Text -->
        <div class="relative z-10 flex flex-col justify-end p-8 xl:p-12 w-full h-full text-on-primary">
            <div class="max-w-xl">
                <h1 class="text-3xl font-semibold mb-4 leading-tight">
                    Efisiensi Distribusi Gizi Untuk Generasi Masa Depan
                </h1>
                <p class="text-lg text-on-primary/80">
                    Platform terintegrasi untuk pemantauan distribusi pangan sekolah yang transparan, akurat, dan tepat waktu ke seluruh penjuru wilayah.
                </p>
            </div>
        </div>
    </section>

    <!-- Right Side: Login Form -->
    <section class="w-full lg:w-1/2 xl:w-2/5 flex items-center justify-center p-4 sm:p-8 bg-surface-container-lowest">
        <div class="w-full max-w-md animate-fade-in">
            <!-- Mobile Branding -->
            <div class="lg:hidden flex flex-col items-center mb-8">
                <div class="bg-primary p-3 rounded-xl mb-4">
                    <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">restaurant</span>
                </div>
                <h1 class="text-2xl text-primary font-bold">SPPG Monitoring</h1>
            </div>

            <!-- Header -->
            <div class="mb-8 text-center lg:text-left">
                <h2 class="text-3xl font-semibold text-on-surface mb-2">Selamat Datang Kembali</h2>
                <p class="text-base text-on-surface-variant">Silakan masuk ke akun Anda untuk memantau distribusi makanan.</p>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">error</span>
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-600 rounded-lg text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">check_circle</span>
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="/login" method="POST" class="space-y-5">
                <?= csrf_field() ?>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-on-surface-variant block" for="username">Email atau Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-outline text-xl group-focus-within:text-primary transition-colors">person</span>
                        </div>
                        <input class="w-full bg-surface border border-outline-variant text-on-surface rounded-lg py-3 pl-11 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none"
                            id="username" name="username" placeholder="nama@sppg.id" type="text" value="<?= old('username') ?>" required/>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-on-surface-variant block" for="password">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-outline text-xl group-focus-within:text-primary transition-colors">lock</span>
                        </div>
                        <input class="w-full bg-surface border border-outline-variant text-on-surface rounded-lg py-3 pl-11 pr-11 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none"
                            id="password" name="password" placeholder="••••••••" type="password" required/>
                        <button class="absolute inset-y-0 right-0 pr-4 flex items-center text-outline hover:text-on-surface transition-colors" onclick="togglePassword()" type="button">
                            <span class="material-symbols-outlined text-xl" id="password-toggle-icon">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between py-1">
                    <div class="flex items-center">
                        <input class="h-4 w-4 text-primary border-outline-variant rounded focus:ring-primary transition-colors cursor-pointer" id="remember-me" name="remember-me" type="checkbox"/>
                        <label class="ml-2 block text-sm font-medium text-on-surface-variant cursor-pointer" for="remember-me">Ingat Saya</label>
                    </div>
                    <a class="text-sm text-primary font-bold hover:text-primary-container transition-colors underline-offset-4 hover:underline" href="#">Lupa Password?</a>
                </div>

                <button class="w-full bg-primary hover:bg-primary-container text-on-primary font-medium text-sm py-4 rounded-lg shadow-sm hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2" type="submit">
                    Login
                    <span class="material-symbols-outlined text-lg">login</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-outline-variant/30 text-center">
                <p class="text-sm text-on-surface-variant">
                    &copy; 2024 SPPG Monitoring System. v2.4.0-stable
                </p>
                <div class="mt-4 flex justify-center gap-8">
                    <a class="text-xs font-semibold text-outline hover:text-primary transition-colors" href="#">Bantuan</a>
                    <a class="text-xs font-semibold text-outline hover:text-primary transition-colors" href="#">Kebijakan Privasi</a>
                    <a class="text-xs font-semibold text-outline hover:text-primary transition-colors" href="#">Keamanan</a>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('password-toggle-icon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.textContent = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            toggleIcon.textContent = 'visibility';
        }
    }

    // Fade In Animation
    document.addEventListener('DOMContentLoaded', () => {
        const mainContent = document.querySelector('.animate-fade-in');
        mainContent.style.opacity = '0';
        mainContent.style.transform = 'translateY(10px)';
        mainContent.style.transition = 'all 0.6s ease-out';
        setTimeout(() => {
            mainContent.style.opacity = '1';
            mainContent.style.transform = 'translateY(0)';
        }, 100);
    });
</script>
</body>
</html>
