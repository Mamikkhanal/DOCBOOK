
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DOCBOOK</title>
        <link rel="icon" href="{{ asset('logo.ico') }}" type="image/x-icon">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    </head>

    <body class="h-screen bg-left bg-no-repeat bg-cover bg-[url('{{ asset('/images/welcome.png') }}')]">
        <div>
            <nav class="">
                {{-- Main Navigation Container --}}
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="relative flex items-center justify-between h-20">
                        {{-- Mobile menu button --}}
                        <div class="absolute inset-y-0 right-0 flex items-center sm:hidden">
                            <button type="button" 
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-900"
                                    onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                                    aria-expanded="false">
                                <span class="sr-only">Open main menu</span>
                                {{-- Icon when menu is closed --}}
                                <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                {{-- Icon when menu is open --}}
                                <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
            
                        {{-- Logo container --}}
                        <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                            <div class="flex-shrink-0 flex items-center">
                                <img class="h-12 w-auto sm:h-16 md:h-20" src="{{ asset('/images/logo.png') }}" alt="logo">
                            </div>
                        </div>
            
                        {{-- Desktop menu --}}
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="flex space-x-4">
                                <a href="/docbook/login"
                                    class="text-sm font-semibold text-gray-100 bg-red-900 px-4 py-2 rounded-xl transition-transform duration-200 hover:scale-105">
                                    Login
                                </a>
                                <a href="/docbook/register"
                                    class="text-sm font-semibold text-gray-100 bg-red-900 px-4 py-2 rounded-xl transition-transform duration-200 hover:scale-105">
                                    Register
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            
                {{-- Mobile menu, toggle classes based on menu state --}}
                <div class="hidden sm:hidden" id="mobile-menu">
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <a href="/docbook/login"
                            class="block text-sm font-semibold text-gray-100 bg-red-900 px-4 py-2 rounded-xl text-center mb-2">
                            Login
                        </a>
                        <a href="/docbook/register"
                            class="block text-sm font-semibold text-gray-100 bg-red-900 px-4 py-2 rounded-xl text-center">
                            Register
                        </a>
                    </div>
                </div>
            </nav>

      <!-- Container -->
<div class="min-h-screen w-full px-4 sm:px-6 lg:px-8 pt-20 lg:pt-40">
    <div class="flex flex-col lg:flex-row w-full justify-between gap-8">
        <!-- Left spacer - hidden on mobile -->
        <div class="hidden lg:block md:block lg:w-1/4 md:w-1/4"></div>

        <!-- Center content -->
        <div class="w-full lg:w-1/2 md:w-1/2 flex flex-col items-center justify-center space-y-6">
            <!-- Main content box -->
            <div class="w-full sm:w-11/12 lg:w-2/3 bg-gray-100 rounded-2xl p-4 sm:p-6">
                <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold tracking-tight bg-gradient-to-r from-red-700 to-blue-800 bg-clip-text text-transparent text-center">
                    DOCBOOK
                </h1>
                <p class="mt-4 sm:mt-6 text-sm sm:text-md leading-6 sm:leading-8 text-red-900 text-justify px-2 sm:px-4">
                    Ease your process to get your doctor's appointment with us. Patients can easily find
                    available doctors and schedule an appointments.
                    We assure you a hassle-free experience.
                </p>
            </div>

            <!-- Action buttons -->
            <div class="w-full sm:w-11/12 lg:w-2/3 flex items-center justify-center gap-x-4 sm:gap-x-6 bg-gray-200 p-3 sm:p-4 rounded-2xl">
                <a href="#" class="rounded-md bg-blue-900 px-3 sm:px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-800 transition-colors duration-200">
                    Get started
                </a>
                <a href="#" class="text-sm font-semibold text-red-800 hover:text-red-900 transition-colors duration-200">
                    Learn more →
                </a>
            </div>
        </div>

       <!-- Right navigation menu -->
       <div class="w-full lg:w-1/4 md:w-1/4 lg:h-2/3">
        <!-- Mobile horizontal menu -->
        <div class="lg:hidden md:hidden flex flex-row justify-center gap-4 flex-wrap">
            <a href="#" class="text-sm font-semibold text-blue-900 px-4 py-2 bg-gray-200 rounded-2xl hover:bg-red-900 hover:text-white transition-colors duration-200">
                Services <span aria-hidden="true">&rarr;</span>
            </a>
            <a href="#" class="text-sm font-semibold text-blue-900 px-4 py-2 bg-gray-200 rounded-2xl hover:bg-red-900 hover:text-white transition-colors duration-200">
                Reviews <span aria-hidden="true">&rarr;</span>
            </a>
            <a href="#" class="text-sm font-semibold text-blue-900 px-4 py-2 bg-gray-200 rounded-2xl hover:bg-red-900 hover:text-white transition-colors duration-200">
                About <span aria-hidden="true">&rarr;</span>
            </a>
            <a href="#" class="text-sm font-semibold text-blue-900 px-4 py-2 bg-gray-200 rounded-2xl hover:bg-red-900 hover:text-white transition-colors duration-200">
                Contact <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
            <!-- Desktop/Tablet vertical menu - show on md and up -->
            <div class="hidden position absolute right-10 top-60 w-32 md:flex md:flex-col space-y-4 bg-gray-200 text-blue-900 text-center p-4 rounded-2xl">
                <a href="#" class="text-sm font-semibold px-2 py-2 hover:bg-red-900 hover:text-white rounded-2xl transition-colors duration-200">
                    Services <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="#" class="text-sm font-semibold px-2 py-2 hover:bg-red-900 hover:text-white rounded-2xl transition-colors duration-200">
                    Reviews <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="#" class="text-sm font-semibold px-2 py-2 hover:bg-red-900 hover:text-white rounded-2xl transition-colors duration-200">
                    About <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="#" class="text-sm font-semibold px-2 py-2 hover:bg-red-900 hover:text-white rounded-2xl transition-colors duration-200">
                    Contact <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</div>

            <footer class="flex justify-center bg-gray-200 text-gray-800 pt-10">
                <div class="flex-col items-center justify-center max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-20 ml-36">

                        <!-- Contact Us Section -->
                        <div>
                            <h4 class="text-lg font-semibold text-blue-900">Contact Us</h4>
                            <p class="mt-2 text-sm">Pokhara Marga, Pokhara, Nepal</p>
                            <p class="text-sm">Phone: +977 9876543210</p>
                            <p class="text-sm">Email: <a href=""
                                    class="text-blue-800 hover:underline">contact@docbook.com</a></p>
                        </div>

                        <!-- Clinic Hours Section -->
                        <div>
                            <h4 class="text-lg font-semibold text-blue-900">Clinic Hours</h4>
                            <p class="mt-2 text-sm">Sun - Thu: 8:00 AM - 8:00 PM</p>
                            <p class="text-sm">Fri: 9:00 AM - 5:00 PM</p>
                            <p class="text-sm">Sat: Closed</p>
                        </div>

                        <!-- Quick Links Section -->
                        <div>
                            <h4 class="text-lg font-semibold text-blue-900">Quick Links</h4>
                            <ul class="mt-2 space-y-2 text-sm">
                                <li><a href="#" class="text-blue-800 hover:underline">Our Services</a></li>
                                <li><a href="#" class="text-blue-800 hover:underline">About Us</a></li>
                                <li><a href="#" class="text-blue-800 hover:underline">Contact</a></li>
                                <li><a href="#" class="text-blue-800 hover:underline">Book Appointment</a></li>
                            </ul>
                        </div>

                    </div>

                    <!-- Footer Bottom -->
                    <div class="bg-200 shadow-md mt-6 border-t border-gray-300 p-1 text-center rounded-2xl">
                        <p class="text-sm text-black">&copy; 2024 DOCBOOK. All rights reserved.</p>
                    </div>
                </div>
            </footer>

    </body>

    </html> 

