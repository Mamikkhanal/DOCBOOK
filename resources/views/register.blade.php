<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-between min-h-screen">
    <div class="w-1/3 ">
    </div>
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-lg min-h-screen">

        <img class="h-20 w-auto ml-36" src="{{ asset('/images/logo.png') }}" alt="logo">
        <h2 class="text-xl font-bold text-center mb-2">Register</h2>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-600 text-white p-4 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" class="w-full h-10 rounded-2xl border border-gray-300 p-3 text-gray-900" value="{{ old('name') }}" required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium mb-1">Email Address <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" class="w-full h-10 rounded-2xl border border-gray-300 p-3 text-gray-900" value="{{ old('email') }}" required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" class="w-full h-10 rounded-2xl border border-gray-300 p-3 text-gray-900" required>
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirm Password <span class="text-red-500">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full h-10 rounded-2xl border border-gray-300 p-3 text-gray-900" required>
            </div>
            

            <div>
                <label for="phone" class="block text-sm font-medium mb-1">Phone Number <span class="text-red-500">*</span></label>
                <input type="tel" id="phone" name="phone" class="w-full h-10 rounded-2xl border border-gray-300 p-3 text-gray-900" value="{{ old('phone') }}" required>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium mb-1">Role <span class="text-red-500">*</span></label>
                <select id="role" name="role" class="w-full h-10 rounded-2xl border border-gray-300 p-2 text-gray-900" required>
                    <option value="">Select Role</option>
                    <option value="doctor" {{ old('role') === 'doctor' ? 'selected' : '' }}>Doctor</option>
                    <option value="patient" {{ old('role') === 'patient' ? 'selected' : '' }}>Patient</option>
                </select>
            </div>

            <!-- Patient Details -->
            <div id="patient-details" class="hidden">
                <div>
                    <label for="age" class="block text-sm font-medium mb-1">Age <span class="text-red-500">*</label>
                    <input type="number" id="age" name="age" class="w-full h-10 rounded-2xl border border-gray-300 p-2 text-gray-900" value="{{ old('age') }}">
                </div>
            </div>

            <!-- Doctor Details -->
            <div id="doctor-details" class="hidden">
                <div>
                    <label for="specialization" class="block text-sm font-medium mb-1">Specialization <span class="text-red-500">*</label>
                    <select id="specialization" name="specialization" class="w-full h-10 rounded-2xl border border-gray-300 p-2 text-gray-900">
                        <option value="">Select Specialization</option>
                        @foreach($specializations as $specialization)
                        <option value="{{ $specialization->name }}" {{ old('specialization') == $specialization->name ? 'selected' : '' }}>
                            {{ $specialization->name }}
                        </option>
                    @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full h-8 bg-blue-900 text-white rounded-2xl p-1 mt-4">Register</button>
        </form>
    </div>
    <div class="w-1/3">
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const patientDetails = document.getElementById('patient-details');
            const doctorDetails = document.getElementById('doctor-details');

            function toggleDetails() {
                const role = roleSelect.value;
                patientDetails.classList.toggle('hidden', role !== 'patient');
                doctorDetails.classList.toggle('hidden', role !== 'doctor');
            }

            roleSelect.addEventListener('change', toggleDetails);
            toggleDetails(); // Initialize on page load
        });
    </script>
</body>
</html>
