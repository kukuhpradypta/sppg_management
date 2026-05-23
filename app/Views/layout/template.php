<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SPPG Management - <?= $title ?? 'Dashboard' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
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
                    "secondary": "#006c49",
                    "secondary-container": "#6cf8bb",
                    "secondary-fixed": "#6ffbbe",
                    "on-secondary": "#ffffff",
                    "surface": "#f8f9ff",
                    "surface-container": "#e5eeff",
                    "surface-container-low": "#eff4ff",
                    "surface-container-high": "#dce9ff",
                    "surface-container-highest": "#d3e4fe",
                    "surface-container-lowest": "#ffffff",
                    "on-surface": "#0b1c30",
                    "on-surface-variant": "#444651",
                    "outline": "#757682",
                    "outline-variant": "#c5c5d3",
                    "error": "#ba1a1a",
                    "error-container": "#ffdad6",
                    "background": "#f8f9ff",
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
    </style>
</head>
<body class="bg-background text-on-surface antialiased">

<!-- Sidebar -->
<?= $this->include('layout/sidebar') ?>

<!-- Main Content -->
<main class="ml-[280px] pt-[64px] min-h-screen">
    <!-- Top Bar -->
    <?= $this->include('layout/topbar') ?>

    <!-- Page Content -->
    <div class="p-6">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Toast Notifications -->
    <?php if (session()->getFlashdata('success')): ?>
    <div id="toast-success" class="fixed top-6 right-6 z-[100] flex items-center gap-3 px-5 py-4 bg-white border border-green-200 rounded-xl shadow-lg transform translate-x-0 transition-all duration-300">
        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-green-600 text-lg">check_circle</span>
        </div>
        <p class="text-sm font-medium text-gray-800"><?= session()->getFlashdata('success') ?></p>
        <button onclick="dismissToast('toast-success')" class="ml-2 text-gray-400 hover:text-gray-600 flex-shrink-0">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div id="toast-error" class="fixed top-6 right-6 z-[100] flex items-center gap-3 px-5 py-4 bg-white border border-red-200 rounded-xl shadow-lg transform translate-x-0 transition-all duration-300">
        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-red-600 text-lg">error</span>
        </div>
        <p class="text-sm font-medium text-gray-800"><?= session()->getFlashdata('error') ?></p>
        <button onclick="dismissToast('toast-error')" class="ml-2 text-gray-400 hover:text-gray-600 flex-shrink-0">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
    <div id="toast-errors" class="fixed top-6 right-6 z-[100] flex items-center gap-3 px-5 py-4 bg-white border border-red-200 rounded-xl shadow-lg transform translate-x-0 transition-all duration-300">
        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-red-600 text-lg">error</span>
        </div>
        <div class="text-sm font-medium text-gray-800">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <p><?= esc($err) ?></p>
            <?php endforeach; ?>
        </div>
        <button onclick="dismissToast('toast-errors')" class="ml-2 text-gray-400 hover:text-gray-600 flex-shrink-0">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>
    <?php endif; ?>
</main>

<script>
    // Toast auto-dismiss
    function dismissToast(id) {
        const toast = document.getElementById(id);
        if (toast) {
            toast.style.transform = 'translateX(120%)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }
    }

    // Auto dismiss all toasts after 3 seconds
    document.addEventListener('DOMContentLoaded', () => {
        const toasts = document.querySelectorAll('[id^="toast-"]');
        toasts.forEach(toast => {
            // Slide in animation
            toast.style.transform = 'translateX(120%)';
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            }, 100);

            // Auto dismiss after 3s
            setTimeout(() => dismissToast(toast.id), 3000);
        });
    });

    // Simple card hover effect
    document.querySelectorAll('[data-hover]').forEach(card => {
        card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-2px)');
        card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
    });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
