<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UMKMBid Dashboard')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#D4AF37',    // Gold
                        secondary: '#B8860B',  // Dark Gold
                        accent: '#FFD700',     // Bright Gold
                        neutral: '#F8F9FA'     // Off White
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .btn-primary {
                @apply bg-primary hover:bg-secondary text-white font-medium py-2 px-4 rounded-lg transition duration-200;
            }
            .btn-secondary {
                @apply bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200;
            }
            .card {
                @apply bg-white rounded-lg shadow-md overflow-hidden;
            }
            .card-header {
                @apply bg-gray-50 px-6 py-4 border-b border-gray-200;
            }
            .card-body {
                @apply p-6;
            }
            .stat-card {
                @apply bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200;
            }
            .action-card {
                @apply bg-white rounded-lg shadow-sm p-6 text-center hover:shadow-md transition-all duration-200 cursor-pointer;
            }
        }
    </style>
</head>
<body class="bg-neutral min-h-screen">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-primary">UMKMBid</a>
                    @auth
                        <div class="hidden md:ml-10 md:flex md:space-x-8">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-lg text-sm font-medium transition duration-200">Dashboard</a>
                                <a href="{{ route('admin.businesses.index') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-lg text-sm font-medium transition duration-200">Businesses</a>
                                <a href="{{ route('admin.auctions.index') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-lg text-sm font-medium transition duration-200">Auctions</a>
                            @elseif(auth()->user()->role === 'umkm_owner')
                                <a href="{{ route('umkm.dashboard') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-lg text-sm font-medium transition duration-200">Dashboard</a>
                                <a href="{{ route('umkm.businesses.index') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-lg text-sm font-medium transition duration-200">My Businesses</a>
                            @elseif(auth()->user()->role === 'investor')
                                <a href="{{ route('investor.dashboard') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-lg text-sm font-medium transition duration-200">Dashboard</a>
                                <a href="{{ route('investor.bids.index') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-lg text-sm font-medium transition duration-200">My Bids</a>
                            @endif
                        </div>
                    @endauth
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('profile.show') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-lg text-sm font-medium transition duration-200 hidden md:inline">{{ auth()->user()->name }}</a>
                        <button id="logout-btn" class="btn-primary text-sm transition duration-200">
                            Logout
                        </button>
                        <!-- Hidden form for logout -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-lg text-sm font-medium transition duration-200">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary text-sm transition duration-200">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <script>
        // Global JavaScript for the application
        
        // Handle logout functionality
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.getElementById('logout-btn');
            const logoutForm = document.getElementById('logout-form');
            
            if (logoutBtn && logoutForm) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Show loading state
                    const originalText = this.textContent;
                    this.textContent = 'Logging out...';
                    this.disabled = true;
                    
                    // Submit the form using fetch for better error handling
                    const formData = new FormData(logoutForm);
                    
                    fetch(logoutForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': getCSRFToken()
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            // Redirect to home page
                            window.location.href = '/';
                        } else {
                            // If fetch fails, fall back to form submission
                            logoutForm.submit();
                        }
                    })
                    .catch(error => {
                        console.log('Fetch failed, using form submission fallback');
                        // Fallback to regular form submission
                        logoutForm.submit();
                    })
                    .finally(() => {
                        // Reset button state in case of error
                        this.textContent = originalText;
                        this.disabled = false;
                    });
                });
            }
        });

        // Helper function to get CSRF token
        function getCSRFToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        }
        
        // Update CSRF token if it changes
        function updateCSRFToken() {
            const token = getCSRFToken();
            const forms = document.querySelectorAll('form input[name="_token"]');
            forms.forEach(input => {
                input.value = token;
            });
        }
    </script>
    
    @yield('scripts')
</body>
</html>