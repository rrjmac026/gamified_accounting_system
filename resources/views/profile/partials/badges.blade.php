<style>
    .badge-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        backdrop-filter: blur(10px);
        background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
        border: 2px solid rgba(236, 72, 153, 0.2);
        box-shadow: 0 8px 32px rgba(236, 72, 153, 0.1);
    }
    
    .badge-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(236, 72, 153, 0.2);
        border-color: rgba(236, 72, 153, 0.4);
    }
    
    .badge-earned {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border: 2px solid #22c55e;
        box-shadow: 0 0 30px rgba(34, 197, 94, 0.3);
        animation: pulse-glow 2s infinite;
    }
    
    .badge-locked {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px dashed rgba(156, 163, 175, 0.5);
        filter: grayscale(40%);
    }
    
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 30px rgba(34, 197, 94, 0.4); }
        50% { box-shadow: 0 0 40px rgba(34, 197, 94, 0.6); }
    }
</style>

<section>
    <header class="flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-semibold text-[#FF92C2]">
                <i class="fas fa-trophy mr-2"></i>My Achievements
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Unlock badges as you progress and gain XP!
            </p>
        </div>
        
        <!-- Experience Level Display -->
        <div class="bg-gradient-to-r from-[#FF92C2] to-purple-500 text-white px-4 py-2 rounded-full shadow-lg">
            <div class="flex items-center space-x-2">
                <i class="fas fa-star text-yellow-300"></i>
                <span class="font-bold">Level {{ $student->level ?? 1 }}</span>
            </div>
            <div class="text-xs opacity-90 text-center">
                {{ $totalXp ?? 0 }} XP
            </div>
        </div>
    </header>

    <div class="mt-6">
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($badges as $badge)
                <div class="badge-card {{ $badge->earned ? 'badge-earned' : 'badge-locked' }} p-4 rounded-2xl text-center group">
                    <div class="relative mb-3">
                        <div class="w-12 h-12 mx-auto {{ $badge->earned ? 'bg-white' : 'bg-gray-100' }} rounded-full flex items-center justify-center shadow-lg">
                            @if($badge->icon_path)
                                <img src="{{ asset('storage/' . $badge->icon_path) }}" 
                                     alt="{{ $badge->name }}" 
                                     class="w-7 h-7 object-contain {{ $badge->earned ? '' : 'grayscale' }}">
                            @else
                                <i class="fas fa-medal {{ $badge->earned ? 'text-yellow-500' : 'text-gray-400' }} text-lg"></i>
                            @endif
                        </div>
                        
                        @if($badge->earned)
                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="font-bold {{ $badge->earned ? 'text-green-800' : 'text-gray-700' }} text-sm mb-1">
                        {{ $badge->name }}
                    </h3>
                    <p class="{{ $badge->earned ? 'text-green-700' : 'text-gray-500' }} text-xs mb-2">
                        {{ $badge->description }}
                    </p>

                    @if($badge->earned)
                        <div class="bg-green-500/20 border border-green-300 rounded-full px-2 py-1">
                            <span class="text-green-800 text-xs font-medium">
                                <i class="fas fa-calendar-check mr-1"></i>
                                Earned
                            </span>
                        </div>
                    @else
                        <div class="bg-gray-100 rounded-full px-2 py-1">
                            <span class="text-gray-500 text-xs">
                                <i class="fas fa-lock mr-1"></i>
                                Locked
                            </span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <div class="text-[#FF92C2] mb-2">
                        <i class="fas fa-trophy text-4xl opacity-50"></i>
                    </div>
                    <p class="text-gray-500">No badges earned yet. Keep going!</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const badgeCards = document.querySelectorAll('.badge-card');
            badgeCards.forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
        });
    </script>
</section>