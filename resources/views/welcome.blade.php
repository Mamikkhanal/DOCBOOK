<Doctype html>

    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DOCBOOK</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    </head>

    <body class="h-screen bg-left bg-no-repeat bg-cover bg-[url('{{ asset('/images/welcome.png') }}')]">
        <div>
            <header class="absolute inset-x-0 top-0 z-50 ">
                <nav class="flex items-center justify-between p-6 lg:px- 8" aria-label="Global">
                    <div class="flex lg:flex-1">
                        <img class="h-20 w-auto ml-8" src="{{ asset('/images/logo.png') }}" alt="logo">
                    </div>

                    <div class="hidden lg:flex lg:flex-1 lg:justify-end space-x-6">
                        <a href="/docbook/login"
                            class="text-sm/6 font-semibold text-gray-100 bg-red-900 px-6 py-2 rounded-2xl scale-100 hover:scale-135">Login</a>
                        <a href="/register"
                            class="text-sm/6 font-semibold text-gray-100 bg-red-900 px-6 py-2 rounded-2xl scale-100hover:scale-135">Register</a>
                    </div>
                </nav>

            </header>

            <!-- Container -->
            <div class="relative px-6 pt-40 lg:px-8 flex felx-row w-full justify-between h-screen">
                <div class='w-1/2'>
                </div>
                <!-- Center Texts -->
                <div
                    class="flex-col items-center text-center justify-center  mt-40 w-full mx-auto px-4 sm:px-6 lg:px-8 h-60">
                    <div class="w-2/3 text-center bg-gray-100 rounded-2xl p-2">
                        <h1
                            class="text-2xl font-bold tracking-tight bg-gradient-to-r from-red-700 to-blue-800 bg-clip-text text-transparent sm:text-center sm:text-6xl">
                            DOCBOOK
                        </h1>
                        <p class="mt-6 text-md leading-8 text-red-900 text-center">
                            Ease your process to get your doctor's appointment with us. Patients can easily find
                            available doctors and schedule an appointments.
                            We assure you a hassle-free experience.
                        </p>
                    </div>
                    <!-- Buttons -->
                    <div class="mt-4 flex items-center justify-center gap-x-6 bg-gray-200 p-2 w-2/3 rounded-2xl">
                        <a href="#"
                            class="rounded-md bg-blue-900 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-00">
                            Get started
                        </a>
                        <a href="#" class="text-sm font-semibold text-red-800">
                            Learn more →
                        </a>
                    </div>
                </div>

                <div
                    class="flex-col lg:flex lg:gap-x-24 w-1/6 h-2/3 space-y-8 bg-gray-200 text-blue-900 text-center p-4 rounded-2xl">
                    <a href="#"
                        class="text-sm/6 font-semibold px-2 py-2 hover:bg-red-900 hover:text-white rounded-2xl">
                        Services
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#"
                        class="text-sm/6 font-semibold px-2 py-2 hover:bg-red-900 hover:text-white rounded-2xl">
                        Reviews
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#"
                        class="text-sm/6 font-semibold px-2 py-2 hover:bg-red-900 hover:text-white rounded-2xl">
                        About
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#"
                        class="text-sm/6 font-semibold px-2 py-2 hover:bg-red-900 hover:text-white rounded-2xl">
                        Contact
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>

            <footer class=" bg-gray-200 text-gray-800 pt-10">
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
