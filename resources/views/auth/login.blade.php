<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - AmikomEventHub</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        }

        .form-input {
            transition: all 0.3s ease;
        }

        .form-input:focus {
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10">

            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-3 mb-4 justify-center">
                    <div class="w-12 h-12 rounded-2xl overflow-hidden">
                        <img src="/assets/logo.webp" alt="AmikomEventHub" class="w-full h-full object-cover">
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-indigo-900">AmikomEventHub</span>
                </div>
                <h1 class="text-3xl font-black text-indigo-900 mb-2">Admin Login</h1>
                <p class="text-slate-500 font-medium">Masuk ke dashboard administrator</p>
            </div>

            <!-- Login Form -->
            <form method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-bold text-slate-700">
                        <i class="fas fa-envelope mr-2 text-indigo-600"></i>Email
                    </label>
                    <input type="email" id="email" name="email" placeholder="admin@example.com" required
                        class="form-input w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none bg-slate-50 hover:border-slate-300">
                    @error('email')
                        <p class="text-red-500 text-sm font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-bold text-slate-700">
                        <i class="fas fa-lock mr-2 text-indigo-600"></i>Password
                    </label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required
                        class="form-input w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none bg-slate-50 hover:border-slate-300">
                    @error('password')
                        <p class="text-red-500 text-sm font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4">
                        <p class="text-red-700 font-bold text-sm">
                            <i class="fas fa-exclamation-circle mr-2"></i>Login Gagal
                        </p>
                        @foreach ($errors->all() as $error)
                            <p class="text-red-600 text-xs mt-1">• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Login Button -->
                <button type="submit"
                    class="w-full gradient-bg text-white font-bold py-3 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:scale-105 mt-8">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login Sekarang
                </button>

            </form>

            <!-- Footer Info -->
            <div class="mt-8 pt-6 border-t border-slate-200">
                <p class="text-center text-xs text-slate-500">
                    Hanya untuk administrator AmikomEventHub
                </p>
            </div>

        </div>

        <!-- Security Info -->
        <div class="mt-6 text-center text-sm text-slate-600">
            <p>
                <i class="fas fa-shield-alt text-indigo-600 mr-1"></i>
                Koneksi Anda diamankan dengan enkripsi tingkat enterprise
            </p>
        </div>

    </div>

</body>

</html>