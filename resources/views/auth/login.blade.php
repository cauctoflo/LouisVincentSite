<x-guest-layout>
    <div class="flex h-screen bg-[#f3f4f6]">
        <!-- Section formulaire (gauche - 1/3) -->
        <div class="w-full md:w-1/3 flex items-center justify-center bg-[#f3f4f6] z-10 p-6">
            <div class="w-full max-w-sm">
                <!-- Logo -->
                <div class="mb-8 flex flex-col items-center">
                    <div class="w-16 h-16 bg-white rounded-lg shadow-md flex items-center justify-center p-2">
                        <img src="{{ asset('storage/assets/images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
                    </div>
                    <span class="mt-3 font-bold text-xl text-gray-800">Lycée Louis Vincent</span>
                </div>

                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Connexion</h2>
                    <p class="text-gray-500 text-sm mt-1">Entrez vos identifiants pour accéder à votre espace</p>
                </div>

                <x-validation-errors class="mb-4" />
                
                @session('status')
                    <div class="mb-4 p-3 bg-green-50 border border-green-100 rounded-lg text-sm text-green-700">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <x-label for="email" value="{{ __('Email') }}" class="text-gray-700 text-sm mb-1.5 font-medium" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <x-input id="email" 
                                class="block w-full pl-10 border-gray-200 focus:border-[#1a4ca1] focus:ring focus:ring-[#1a4ca1]/20 focus:ring-opacity-50 rounded-lg" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                placeholder="nom@louis-vincent.fr"
                                required 
                                autofocus 
                                autocomplete="username" />
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <x-label for="password" value="{{ __('Password') }}" class="text-gray-700 text-sm font-medium" />
                            
                            @if (Route::has('password.request'))
                                <a class="text-xs text-gray-500 hover:text-[#1a4ca1] hover:underline" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <x-input id="password" 
                                class="block w-full pl-10 border-gray-200 focus:border-[#1a4ca1] focus:ring focus:ring-[#1a4ca1]/20 focus:ring-opacity-50 rounded-lg" 
                                type="password" 
                                name="password"
                                placeholder="••••••••" 
                                required 
                                autocomplete="current-password" />
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" class="text-[#1a4ca1] rounded border-gray-300" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full py-3 bg-[#1a4ca1] hover:bg-[#15397d] text-white font-medium rounded-lg transition-colors duration-200 flex items-center justify-center group">
                            {{ __('Log in') }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Note de confidentialité -->
                <div class="mt-6 text-center text-xs text-gray-500">
                    <p>En vous connectant, vous acceptez les conditions d'utilisation et la politique de confidentialité du Lycée Louis Vincent.</p>
                </div>
            </div>
        </div>

        <!-- Section image (droite - 2/3) -->
        <div class="hidden md:block md:w-2/3 relative overflow-hidden">
            <!-- Image qui couvre la section -->
            <img src="https://cdn-s-www.republicain-lorrain.fr/images/391162B3-32F3-48E3-9276-C99B7EB59B7C/NW_detail/le-lycee-louis-vincent-existe-depuis-plus-de-100-ans-ses-portes-cachent-des-curiosites-meconnues-photo-rl-karim-siari-1697451626.jpg" 
                 alt="Lycée Louis Vincent" 
                 class="object-cover object-center w-full h-full scale-110">
            
            <!-- Overlay gradient - Amélioré -->
            <div class="absolute inset-0 bg-gradient-to-l from-[#000]/80 via-[#1a4ca1]/50 to-[#000]/70"></div>
            <div class="absolute inset-0 bg-[#1a4ca1]/20"></div>
            
            <!-- Contenu texte sur l'image -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="max-w-2xl text-center text-white px-8">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-[#f3f4f6]/10 mb-6">
                        <span class="w-1.5 h-1.5 bg-[#f3f4f6] rounded-full mr-2"></span>
                        Établissement d'excellence depuis 1892
                    </span>
                    <h1 class="text-5xl font-bold leading-tight mb-4">
                        Accès <span class="text-white/80">Restreint</span>
                    </h1>
                    <p class="text-white/70 text-xl max-w-xl mx-auto font-light">
                        Espace réservé aux enseignants et à l'administration du Lycée Louis Vincent.
                    </p>
                </div>
            </div>
            
            <!-- Élément décoratif en bas -->
            <div class="absolute bottom-8 left-0 right-0 flex items-center justify-center space-x-4">
                <div class="h-[1px] w-12 bg-[#f3f4f6]/30"></div>
                <p class="text-white/50 text-sm">Un lycée d'excellence au service de votre réussite</p>
                <div class="h-[1px] w-12 bg-[#f3f4f6]/30"></div>
            </div>
        </div>
    </div>
</x-guest-layout>
