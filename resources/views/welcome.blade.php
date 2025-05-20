<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chat App') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Styles -->
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 50%, #EC4899 100%);
        }
        .auth-card {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen hero-gradient flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white bg-opacity-10 border-b border-white border-opacity-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span class="ml-2 text-xl font-bold text-white">ChatApp</span>
                        </div>
                    </div>

                    <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium">Se connecter</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-white text-indigo-600 hover:bg-gray-100 px-4 py-2 rounded-md text-sm font-medium transition duration-150">Créer un compte</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Column - Text Content -->
                    <div class="text-center lg:text-left">
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
                            Connectez-vous avec le monde entier
                        </h1>
                        <p class="text-xl text-white text-opacity-90 mb-8 max-w-lg">
                            Une application de chat en temps réel simple, sécurisée et intuitive. Discutez avec vos amis, famille et collègues sans limites.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="bg-white text-indigo-600 hover:bg-gray-100 px-6 py-3 rounded-lg text-lg font-medium transition duration-150 shadow-lg">
                                        Aller au chat
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="bg-white text-indigo-600 hover:bg-gray-100 px-6 py-3 rounded-lg text-lg font-medium transition duration-150 shadow-lg">
                                        Se connecter
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="bg-indigo-700 text-white hover:bg-indigo-800 px-6 py-3 rounded-lg text-lg font-medium transition duration-150 border border-white border-opacity-30 shadow-lg">
                                            Créer un compte
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>

                    <!-- Right Column - Auth Card -->
                    <div class="auth-card rounded-2xl shadow-2xl overflow-hidden">
                        @if (Route::has('login'))
                            @auth
                                <div class="p-8 text-center">
                                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Bienvenue de retour !</h2>
                                    <p class="text-gray-600 mb-6">Vous êtes déjà connecté. Accédez à votre tableau de bord pour commencer à chatter.</p>
                                    <a href="{{ url('/dashboard') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition duration-150">
                                        Aller au dashboard
                                    </a>
                                </div>
                            @else
                                <!-- Login Form -->
                                <div x-data="{ activeTab: 'login' }" class="p-8">
                                    <!-- Tabs -->
                                    <div class="flex border-b border-gray-200 mb-6">
                                        <button @click="activeTab = 'login'" 
                                                :class="{ 'border-b-2 border-indigo-500 text-indigo-600': activeTab === 'login' }"
                                                class="py-2 px-4 font-medium text-sm focus:outline-none">
                                            Connexion
                                        </button>
                                        @if (Route::has('register'))
                                            <button @click="activeTab = 'register'" 
                                                    :class="{ 'border-b-2 border-indigo-500 text-indigo-600': activeTab === 'register' }"
                                                    class="py-2 px-4 font-medium text-sm focus:outline-none">
                                                Inscription
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Login Tab -->
                                    <div x-show="activeTab === 'login'" x-transition>
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf

                                            <!-- Email -->
                                            <div class="mb-4">
                                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                <input id="email" name="email" type="email" required autofocus
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                @error('email')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Password -->
                                            <div class="mb-4">
                                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                                                <input id="password" name="password" type="password" required
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                @error('password')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Remember Me -->
                                            <div class="flex items-center mb-4">
                                                <input id="remember_me" name="remember" type="checkbox" 
                                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                                    Se souvenir de moi
                                                </label>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="mb-4">
                                                <button type="submit" 
                                                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Se connecter
                                                </button>
                                            </div>

                                            <!-- Forgot Password -->
                                            @if (Route::has('password.request'))
                                                <div class="text-center">
                                                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                                                        Mot de passe oublié ?
                                                    </a>
                                                </div>
                                            @endif
                                        </form>
                                    </div>

                                    <!-- Register Tab -->
                                    @if (Route::has('register'))
                                        <div x-show="activeTab === 'register'" x-transition>
                                            <form method="POST" action="{{ route('register') }}">
                                                @csrf

                                                <!-- Name -->
                                                <div class="mb-4">
                                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                                    <input id="name" name="name" type="text" required autofocus
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                    @error('name')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Email -->
                                                <div class="mb-4">
                                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                    <input id="email" name="email" type="email" required
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                    @error('email')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Password -->
                                                <div class="mb-4">
                                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                                                    <input id="password" name="password" type="password" required
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                    @error('password')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Confirm Password -->
                                                <div class="mb-4">
                                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                                                    <input id="password_confirmation" name="password_confirmation" type="password" required
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>

                                                <!-- Submit Button -->
                                                <div>
                                                    <button type="submit" 
                                                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        S'inscrire
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </main>

        <!-- Features Section -->
        <section class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Pourquoi choisir notre application ?</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="text-center p-6 rounded-lg">
                        <div class="mx-auto h-12 w-12 text-indigo-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Temps réel</h3>
                        <p class="text-gray-600">
                            Messages instantanés sans avoir à rafraîchir la page. Une expérience de chat fluide et réactive.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="text-center p-6 rounded-lg">
                        <div class="mx-auto h-12 w-12 text-indigo-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Sécurisé</h3>
                        <p class="text-gray-600">
                            Cryptage des données et authentification robuste. Vos conversations restent privées.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="text-center p-6 rounded-lg">
                        <div class="mx-auto h-12 w-12 text-indigo-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Simple à utiliser</h3>
                        <p class="text-gray-600">
                            Interface intuitive conçue pour une prise en main immédiate. Aucune configuration compliquée.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span class="ml-2 text-xl font-bold">ChatApp</span>
                        </div>
                        <p class="mt-2 text-sm text-gray-400">
                            Connectez-vous avec ceux qui comptent vraiment.
                        </p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">GitHub</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-800 pt-8 md:flex md:items-center md:justify-between">
                    <p class="text-base text-gray-400 text-center md:text-left">
                        &copy; ilias AFATHI - soukaina HABET 2025 ChatApp. Tous droits réservés.
                    </p>
                    <div class="mt-4 md:mt-0 flex justify-center md:justify-end space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white text-sm">Confidentialité</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm">Conditions</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm">Contact</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>