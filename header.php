<?php
include_once __DIR__ . '/config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniFind - Centralized Lost & Found Portal</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        luxury: {
                            950: '#060a17',
                            900: '#0b132b',
                            800: '#1c2541',
                            700: '#3a506b',
                            gold: '#d4af37',
                            goldLight: '#f3c68f',
                            goldDark: '#aa820a',
                            accent: '#e0a96d'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body class="bg-slate-100 text-slate-800 flex flex-col min-h-screen font-sans">

    <!-- Top Luxury Header -->
    <header class="bg-luxury-900/95 backdrop-blur-md text-white shadow-2xl sticky top-0 z-40 border-b border-luxury-gold/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 gap-4">
                <!-- Brand Logo -->
                <a href="index.php" class="flex items-center space-x-3 shrink-0 group">
                    <div class="w-11 h-11 bg-gradient-to-br from-luxury-gold to-amber-600 text-luxury-900 rounded-xl flex items-center justify-center font-extrabold text-xl shadow-lg border border-amber-300/40 group-hover:scale-105 transition">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <span class="font-black text-2xl tracking-tight bg-gradient-to-r from-white via-slate-100 to-luxury-goldLight bg-clip-text text-transparent">UniFind</span>
                        <span class="hidden lg:inline-block text-[10px] uppercase tracking-wider bg-luxury-800 text-luxury-gold px-2.5 py-0.5 rounded-full ml-2 font-bold border border-luxury-gold/30">University of Vavuniya</span>
                    </div>
                </a>

                <!-- Nav Menu -->
                <nav class="flex items-center space-x-1 sm:space-x-2">
                    <a href="index.php" class="px-3.5 py-2 rounded-xl text-sm font-medium text-slate-200 hover:bg-luxury-800 hover:text-white transition flex items-center">
                        <i class="fa-solid fa-house mr-2 text-luxury-gold"></i> Home
                    </a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="dashboard.php" class="px-3.5 py-2 rounded-xl text-sm font-medium text-slate-200 hover:bg-luxury-800 hover:text-white transition flex items-center">
                            <i class="fa-solid fa-user-gear mr-2 text-luxury-gold"></i> My Dashboard
                        </a>
                    <?php endif; ?>
                    
                    <!-- Admin Panel Link - ALWAYS VISIBLE IN HEADER -->
                    <a href="admin.php" class="px-3.5 py-2 rounded-xl text-sm font-bold text-luxury-gold bg-luxury-gold/10 hover:bg-luxury-gold/20 transition flex items-center border border-luxury-gold/40 shadow-sm">
                        <i class="fa-solid fa-shield-halved mr-2"></i> Admin Panel
                    </a>
                </nav>

                <!-- Actions & Profile -->
                <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                    <a href="report_lost.php" class="bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-500 hover:to-rose-600 text-white px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold shadow-md transition flex items-center">
                        <i class="fa-solid fa-circle-plus mr-1.5"></i> Report Lost
                    </a>
                    <a href="report_found.php" class="bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-500 hover:to-teal-600 text-white px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold shadow-md transition flex items-center">
                        <i class="fa-solid fa-hand-holding-heart mr-1.5"></i> Report Found
                    </a>

                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <div class="flex items-center space-x-2 border-l border-luxury-700 pl-3">
                            <a href="login.php" class="text-xs bg-luxury-800 text-slate-200 hover:bg-luxury-700 hover:text-white px-3.5 py-2.5 rounded-xl font-medium transition border border-slate-700">Login</a>
                            <a href="register.php" class="text-xs btn-gold px-3.5 py-2.5 rounded-xl font-bold shadow transition">Register</a>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center space-x-2 border-l border-luxury-700 pl-3">
                            <a href="dashboard.php" class="flex items-center space-x-2">
                                <div class="w-9 h-9 rounded-xl border-2 border-luxury-gold bg-luxury-800 text-luxury-gold flex items-center justify-center font-bold text-xs shadow-inner">
                                    <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
                                </div>
                                <span class="text-xs font-semibold text-slate-200 hidden md:inline"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                            </a>
                            <a href="logout.php" title="Logout" class="text-xs text-red-300 hover:text-white bg-red-900/40 hover:bg-red-800/60 p-2.5 rounded-xl transition border border-red-500/20">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
