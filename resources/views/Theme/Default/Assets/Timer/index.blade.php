@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Countdown Timer Component - Refonte premium -->
<div id="inauguration-countdown" class="fixed top-0 left-0 w-full h-full flex items-center justify-center z-50">
    <!-- Arrière-plan vidéo amélioré comme dans la page d'accueil -->
    <div class="fixed inset-0 z-[-2] overflow-hidden">
        <!-- Effet de vignette pour ajouter de la profondeur -->
        <div class="absolute inset-0 vignette z-[3]"></div>
        
        <!-- Superposition de dégradé animé -->
        <div class="absolute inset-0 video-overlay z-[2]"></div>
        
        <!-- Dégradé maillé léger pour la texture -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-secondary/30 opacity-80 z-[1]"></div>
        
        <!-- Élément vidéo avec des paramètres de qualité améliorés -->
        <video class="min-w-full min-h-full object-cover absolute filter brightness-110 contrast-105 saturate-105" autoplay muted loop playsinline>
            <source src="{{ asset('storage/assets/video/videotest.webm') }}" type="video/webm">
            Votre navigateur ne prend pas en charge la lecture de vidéos.
        </video>
    </div>

    <!-- Contenu principal -->
    <div class="relative z-10 max-w-6xl mx-auto w-full px-6">
        <div class="text-center mb-12">
            <!-- Badge inspiré de la page d'accueil -->
            <div class="inline-block px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white text-sm font-semibold mb-8">
                <span class="mr-2 text-secondary-light">•</span> Établissement d'excellence depuis 1892
            </div>
            
            <!-- Titre principal dans le style de la page d'accueil -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-display font-extrabold text-white leading-tight mb-6">
                Lancement du 
                <span class="relative inline-block">
                    <span class="relative z-10 text-secondary-light">nouveau site</span>
                    <span class="absolute -bottom-3 left-0 right-0 h-1 bg-secondary-light opacity-30"></span>
                </span>
            </h1>
            
            <p class="text-white/90 text-lg md:text-xl max-w-2xl mx-auto mb-10">
                Une nouvelle ère numérique pour le Lycée Louis Vincent. Préparez-vous pour une expérience interactive.
            </p>
        </div>

        <!-- Compteur en glass cards premium -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 max-w-4xl mx-auto mb-16">
            <!-- Jours -->
            <div class="glass rounded-2xl p-6 transform hover:-rotate-1 transition-all duration-300 hover:scale-105 hover:shadow-xl border border-white/10 group hover:border-white/30 hover:bg-white/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-primary-dark/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="shimmer opacity-0 group-hover:opacity-20"></div>
                <div class="number-animation text-5xl md:text-6xl font-display font-extrabold text-white mb-2 group-hover:text-secondary-light transition-colors" id="days" data-value="00">00</div>
                <span class="text-white/80 text-sm uppercase tracking-widest">Jours</span>
            </div>
            
            <!-- Heures -->
            <div class="glass rounded-2xl p-6 transform hover:rotate-1 transition-all duration-300 hover:scale-105 hover:shadow-xl border border-white/10 group hover:border-white/30 hover:bg-white/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-secondary/20 to-secondary-dark/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="shimmer opacity-0 group-hover:opacity-20"></div>
                <div class="number-animation text-5xl md:text-6xl font-display font-extrabold text-white mb-2 group-hover:text-secondary-light transition-colors" id="hours" data-value="00">00</div>
                <span class="text-white/80 text-sm uppercase tracking-widest">Heures</span>
            </div>
            
            <!-- Minutes -->
            <div class="glass rounded-2xl p-6 transform hover:-rotate-1 transition-all duration-300 hover:scale-105 hover:shadow-xl border border-white/10 group hover:border-white/30 hover:bg-white/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-primary-dark/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="shimmer opacity-0 group-hover:opacity-20"></div>
                <div class="number-animation text-5xl md:text-6xl font-display font-extrabold text-white mb-2 group-hover:text-secondary-light transition-colors" id="minutes" data-value="00">00</div>
                <span class="text-white/80 text-sm uppercase tracking-widest">Minutes</span>
            </div>
            
            <!-- Secondes -->
            <div class="glass rounded-2xl p-6 transform hover:rotate-1 transition-all duration-300 hover:scale-105 hover:shadow-xl border border-white/10 group hover:border-white/30 hover:bg-white/10 relative overflow-hidden pulse-countdown">
                <div class="absolute inset-0 bg-gradient-to-br from-secondary/20 to-secondary-dark/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="shimmer opacity-0 group-hover:opacity-20"></div>
                <div class="number-animation text-5xl md:text-6xl font-display font-extrabold text-white mb-2 group-hover:text-secondary-light transition-colors" id="seconds" data-value="00">00</div>
                <span class="text-white/80 text-sm uppercase tracking-widest">Secondes</span>
            </div>
        </div>
        
        <!-- Scroll indicator comme dans la page d'accueil -->
        <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2 text-white opacity-80 animate-bounce">
            <div class="relative w-10 h-10 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-2 border-white/30 animate-pulse"></div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>

<!-- Animation de chargement et de célébration -->
<div id="celebration-animation" class="fixed inset-0 z-[100] hidden">
    <!-- Loading Animation (style Louis Vincent) -->
    <div class="loader-wrapper">
        <div class="premium-loader">
            <div class="premium-loader-inner">
                <div class="dot-container">
                    <div class="dot dot1"></div>
                    <div class="dot dot2"></div>
                    <div class="dot dot3"></div>
                </div>
                <div class="Louis-Vincent-logo">
                    <div class="logo-text">LOUIS VINCENT</div>
                    <div class="loader-bar">
                        <div class="loader-progress"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Confetti canvas -->
    <canvas id="confetti-canvas" class="fixed inset-0 z-[101] pointer-events-none"></canvas>
    
    <!-- Final reveal inspiré du style Louis Vincent -->
    <div id="final-reveal" class="fixed inset-0 flex items-center justify-center opacity-0 z-[102]">
        <div class="relative w-full h-full">
            <!-- Background video reveal -->
            <div class="absolute inset-0 z-[-1] overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/70 to-secondary/70 z-[1]"></div>
                <video class="min-w-full min-h-full object-cover absolute filter brightness-75" autoplay muted loop playsinline>
                    <source src="{{ asset('storage/assets/video/videotest.webm') }}" type="video/webm">
                </video>
            </div>
            
            <!-- Content -->
            <div class="text-center transform scale-150 opacity-0 transition-all duration-1000" id="reveal-content">
                <!-- Logo avec effet premium -->
                <div class="relative inline-block mb-8">
                    <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center shadow-2xl mb-6 mx-auto">
                        <div class="absolute inset-0 rounded-full animate-pulse-slow bg-white/30 blur-xl"></div>
                        <img src="{{ asset('storage/assets/images/logo.png') }}" alt="Louis Vincent" class="w-20 h-20 object-contain relative z-10" onerror="this.src='https://placeholder.pics/svg/100/FFFFFF/000000/LV';this.onerror='';">
                    </div>
                </div>
                
                <!-- Titre élégant -->
                <h1 class="text-6xl md:text-8xl font-display font-extrabold text-white leading-tight mb-6">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-white/80">BIENVENUE</span>
                </h1>
                
                <p class="text-2xl md:text-3xl text-white/90 mb-10 max-w-3xl mx-auto">
                    Le nouveau site du Lycée Louis Vincent est officiellement lancé !
                </p>
                
                <!-- Bouton inspiré de la navbar -->
                <a href="#" class="inline-flex items-center relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-secondary to-primary rounded-full blur-sm opacity-70 group-hover:opacity-100 transition duration-300"></div>
                    <button id="enter-site" class="relative bg-white hover:bg-gray-50 text-primary-dark font-bold px-8 py-4 rounded-full transition-all transform group-hover:scale-105 group-hover:shadow-lg">
                        <span class="flex items-center">
                            <span>Découvrir le site</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Style premium pour le compteur */
.glass {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.06);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.vignette {
    background: radial-gradient(ellipse at center, transparent 0%, rgba(0, 0, 0, 0.6) 100%);
}

.video-overlay {
    background: linear-gradient(to bottom, rgba(17, 24, 39, 0.4), rgba(17, 24, 39, 0.7));
}

/* Animation de brillance au survol */
.shimmer {
    background: linear-gradient(to right, transparent 0%, rgba(255, 255, 255, 0.2) 50%, transparent 100%);
    position: absolute;
    top: 0;
    left: -100%;
    width: 300%;
    height: 100%;
    animation: shimmer 2s infinite;
    transform: skewX(-20deg);
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Animation de défilement pour les nombres */
.number-animation {
    position: relative;
    display: inline-block;
    overflow: hidden;
}

.number-animation::after {
    content: attr(data-value);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(to bottom, transparent, transparent);
}

@keyframes countUp {
    0% {
        transform: translateY(100%);
        opacity: 0;
    }
    15% {
        opacity: 1;
    }
    85% {
        opacity: 1;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Effet de brillance lorsque les nombres changent */
.text-glow {
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(247, 204, 51, 0.8);
    color: #f7cc33 !important;
    transition: all 0.3s ease;
}

/* Pulsation pour la carte des secondes */
.pulse-countdown {
    animation: pulse-countdown 1s infinite alternate;
}

@keyframes pulse-countdown {
    0% { transform: scale(1); }
    100% { transform: scale(1.03) rotate(1deg); }
}

/* Animation de pulsation lente */
@keyframes pulse-slow {
    0% { opacity: 0.5; transform: scale(1); }
    50% { opacity: 0.2; transform: scale(1.2); }
    100% { opacity: 0.5; transform: scale(1); }
}

.animate-pulse-slow {
    animation: pulse-slow 3s infinite;
}

/* Loader premium pour Louis Vincent */
.loader-wrapper {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle at center, rgba(17, 24, 39, 0.7) 0%, rgba(17, 24, 39, 0.95) 100%);
}

.premium-loader {
    width: 320px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.premium-loader-inner {
    text-align: center;
}

.dot-container {
    display: flex;
    justify-content: center;
    margin-bottom: 30px;
}

.dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    margin: 0 8px;
    background-color: var(--secondary-light, #f59e0b);
    animation: dot-bounce 1.2s infinite ease-in-out;
}

.dot1 { animation-delay: 0s; }
.dot2 { animation-delay: 0.2s; }
.dot3 { animation-delay: 0.4s; }

@keyframes dot-bounce {
    0%, 100% { transform: scale(0.8); background-color: var(--secondary-light, #f59e0b); }
    50% { transform: scale(1.2); background-color: var(--primary, #2563eb); }
}

.Louis-Vincent-logo {
    margin-top: 1rem;
}

.logo-text {
    color: white;
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: 20px;
    letter-spacing: 2px;
    margin-bottom: 12px;
}

.loader-bar {
    width: 240px;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
    margin: 0 auto;
}

.loader-progress {
    height: 100%;
    width: 0;
    background: linear-gradient(to right, var(--primary, #2563eb), var(--secondary-light, #f59e0b));
    animation: progress 3s ease-in-out forwards;
}

@keyframes progress {
    0% { width: 0; }
    100% { width: 100%; }
}

/* Debug message */
#debug-message {
    position: fixed;
    bottom: 10px;
    left: 10px;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 10px;
    border-radius: 5px;
    z-index: 9999;
    max-width: 300px;
    display: none;
}
</style>

<!-- Debug message div -->
<div id="debug-message"></div>

<script>
// Debug logging function
function debugLog(message) {
    console.log(message);
    const debugEl = document.getElementById('debug-message');
    if (debugEl) {
        debugEl.style.display = 'block';
        debugEl.textContent = message;
    }
}

// Animation de défilement des nombres
function animateValue(element, start, end, duration) {
    // Sauvegarder la valeur cible comme attribut data-value
    element.setAttribute('data-value', end.toString().padStart(2, '0'));
    
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        
        // Pour les petites valeurs, nous utilisons une animation spéciale
        if (end < 10) {
            // Animation par défilement visuel
            element.innerHTML = end.toString().padStart(2, '0');
            element.style.animation = 'countUp 0.5s ease-out';
            setTimeout(() => {
                element.style.animation = 'none';
            }, 500);
        } else {
            // Pour les grands nombres, nous utilisons une animation incrémentale
            const value = Math.floor(progress * (end - start) + start);
            element.innerHTML = value.toString().padStart(2, '0');
            
            // Ajouter un effet visuel
            element.classList.add('text-glow');
            setTimeout(() => {
                element.classList.remove('text-glow');
            }, 100);
        }
        
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    
    window.requestAnimationFrame(step);
}

// Initialize the timer immediately and on DOM load
initCountdown();
document.addEventListener('DOMContentLoaded', initCountdown);

// Function to initialize countdown
function initCountdown() {
    try {
        debugLog('Initializing countdown...');
        
        // Get DOM elements
        const countdownDiv = document.getElementById('inauguration-countdown');
        const daysEl = document.getElementById('days');
        const hoursEl = document.getElementById('hours');
        const minutesEl = document.getElementById('minutes');
        const secondsEl = document.getElementById('seconds');
        
        // Verify elements exist
        if (!countdownDiv || !daysEl || !hoursEl || !minutesEl || !secondsEl) {
            debugLog('Error: One or more countdown elements not found!');
            return;
        }
        
        // Set the inauguration date - CHANGE THIS TO YOUR ACTUAL DATE
        const inaugurationType = "fixed"; // "fixed" for specific date or "demo" for testing
        
        let inaugurationDate;
        
        if (inaugurationType === "fixed") {
            // Fixed date for the actual event
            inaugurationDate = new Date("June 25, 2025 15:00:00").getTime();
            debugLog('Using fixed date: June 25, 2025 15:00:00');
        } else {
            // Demo mode - countdown for a short time
            inaugurationDate = new Date().getTime() + (3 * 60 * 1000); // 3 minutes
            debugLog('Using demo mode: 3 minutes countdown');
        }
        
        // Variables to track previous values for animation
        let prevDays = 0;
        let prevHours = 0;
        let prevMinutes = 0;
        let prevSeconds = 0;
        
        // Update countdown every second
        const countdown = setInterval(function() {
            try {
                // Get current time
                const now = new Date().getTime();
                
                // Time remaining
                const distance = inaugurationDate - now;
                
                // Calculate time units
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Utiliser l'animation seulement si les valeurs changent
                if (days !== prevDays) {
                    animateValue(daysEl, prevDays, days, 500);
                    prevDays = days;
                }
                
                if (hours !== prevHours) {
                    animateValue(hoursEl, prevHours, hours, 500);
                    prevHours = hours;
                }
                
                if (minutes !== prevMinutes) {
                    animateValue(minutesEl, prevMinutes, minutes, 500);
                    prevMinutes = minutes;
                }
                
                if (seconds !== prevSeconds) {
                    animateValue(secondsEl, prevSeconds, seconds, 500);
                    prevSeconds = seconds;
                }
                
                // Add effects for final countdown
                if (distance <= 10000 && distance > 0) {  // Last 10 seconds
                    secondsEl.classList.add("text-secondary-light", "animate-pulse");
                    secondsEl.parentElement.classList.add("border-secondary-light");
                }
                
                // When countdown finishes
                if (distance <= 0) {
                    clearInterval(countdown);
                    debugLog('Countdown complete, starting celebration');
                    startCelebration();
                }
            } catch (err) {
                debugLog('Error in countdown: ' + err.message);
                clearInterval(countdown);
            }
        }, 1000);
        
        debugLog('Countdown successfully initialized');
    } catch (err) {
        debugLog('Initialization error: ' + err.message);
    }
}

// Function to start the celebration sequence
function startCelebration() {
    try {
        // Fade out countdown
        const countdownEl = document.getElementById("inauguration-countdown");
        countdownEl.classList.add("transition-opacity", "duration-1000", "opacity-0");
        
        setTimeout(() => {
            countdownEl.style.display = "none";
            
            // Show loading animation
            const celebrationEl = document.getElementById("celebration-animation");
            celebrationEl.classList.remove("hidden");
            
            // After loading animation, show final reveal
            setTimeout(() => {
                // Hide loader
                document.querySelector(".loader-wrapper").classList.add("transition-opacity", "duration-500", "opacity-0");
                
                // Start confetti
                startConfetti();
                
                // Show reveal message
                setTimeout(() => {
                    const finalReveal = document.getElementById("final-reveal");
                    finalReveal.style.opacity = "1";
                    
                    setTimeout(() => {
                        const revealContent = document.getElementById("reveal-content");
                        revealContent.style.transform = "scale(1)";
                        revealContent.style.opacity = "1";
                        
                        // Add event listener to "Enter Site" button
                        document.getElementById('enter-site').addEventListener('click', function() {
                            finalReveal.classList.add("transition-opacity", "duration-1000", "opacity-0");
                            setTimeout(() => {
                                celebrationEl.classList.add("hidden");
                            }, 1000);
                        });
                    }, 500);
                }, 1000);
            }, 3000);
        }, 1000);
    } catch (err) {
        debugLog('Error in celebration sequence: ' + err.message);
    }
}

// Confetti animation with Louis Vincent colors
function startConfetti() {
    try {
        const canvas = document.getElementById("confetti-canvas");
        if (!canvas) {
            debugLog('Confetti canvas not found');
            return;
        }
        
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        const ctx = canvas.getContext("2d");
        const pieces = [];
        const numberOfPieces = 300;
        
        // School colors - Primary and Secondary
        const colors = [
            "#2563eb", "#1d4ed8", "#3b82f6", // Primary blues
            "#f59e0b", "#d97706", "#fbbf24", // Secondary oranges/golds
            "#ffffff", "#f3f4f6"              // Whites
        ];
        
        // Create confetti pieces with various shapes
        for (let i = 0; i < numberOfPieces; i++) {
            const shape = Math.random() > 0.6 ? 'circle' : (Math.random() > 0.5 ? 'rect' : 'star');
            pieces.push({
                x: Math.random() * canvas.width,
                y: Math.random() * -canvas.height,
                rotation: Math.random() * 360,
                size: Math.random() * (15 - 5) + 5,
                color: colors[Math.floor(Math.random() * colors.length)],
                speed: Math.random() * (8 - 2) + 2,
                opacity: 1,
                shape: shape
            });
        }
        
        // Animation loop
        function animateConfetti() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            pieces.forEach((piece, index) => {
                ctx.save();
                ctx.translate(piece.x, piece.y);
                ctx.rotate(piece.rotation * Math.PI / 180);
                ctx.fillStyle = piece.color;
                ctx.globalAlpha = piece.opacity;
                
                if (piece.shape === 'circle') {
                    ctx.beginPath();
                    ctx.arc(0, 0, piece.size/2, 0, Math.PI * 2);
                    ctx.fill();
                } else if (piece.shape === 'rect') {
                    ctx.fillRect(-piece.size / 2, -piece.size / 2, piece.size, piece.size);
                } else if (piece.shape === 'star') {
                    drawStar(ctx, 0, 0, 5, piece.size/2, piece.size/4);
                }
                
                ctx.restore();
                
                // Update position
                pieces[index].y += piece.speed;
                pieces[index].rotation += 2;
                
                // If out of view, reset position
                if (piece.y > canvas.height) {
                    pieces[index].y = -piece.size;
                    pieces[index].x = Math.random() * canvas.width;
                }
            });
            
            requestAnimationFrame(animateConfetti);
        }
        
        // Function to draw star shape
        function drawStar(ctx, cx, cy, spikes, outerRadius, innerRadius) {
            let rot = Math.PI / 2 * 3;
            let x = cx;
            let y = cy;
            let step = Math.PI / spikes;

            ctx.beginPath();
            ctx.moveTo(cx, cy - outerRadius);
            
            for (let i = 0; i < spikes; i++) {
                x = cx + Math.cos(rot) * outerRadius;
                y = cy + Math.sin(rot) * outerRadius;
                ctx.lineTo(x, y);
                rot += step;

                x = cx + Math.cos(rot) * innerRadius;
                y = cy + Math.sin(rot) * innerRadius;
                ctx.lineTo(x, y);
                rot += step;
            }
            
            ctx.lineTo(cx, cy - outerRadius);
            ctx.closePath();
            ctx.fill();
        }
        
        animateConfetti();
    } catch (err) {
        debugLog('Error in confetti animation: ' + err.message);
    }
}

// Debug functions
window.skipToAnimation = function() {
    debugLog('Skipping to animation');
    startCelebration();
};

window.forceInit = function() {
    debugLog('Force initializing countdown');
    initCountdown();
};
</script>
