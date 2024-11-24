<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book an Appointment</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row items-start justify-center gap-8 max-w-7xl mx-auto">
        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-lg p-8 w-full lg:w-3/5">
            <!-- Header -->
            <div class="bg-gradient-to-br from-blue-800 to-blue-900  p-6 rounded-lg mb-6">
                <h1 class="text-2xl font-bold text-white text-center">
                    Book an Appointment
                </h1>
                <p class="mt-2 text-white text-center text-sm">
                    Schedule your consultation with our medical professionals
                </p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('appointment.store') }}" class="space-y-6">
                @csrf

                <!-- Doctor Selection -->
                <div class="space-y-2">
                    <label for="doctor" class="block text-sm font-medium text-gray-700">
                        Select Doctor
                    </label>
                    <select
                        id="doctor"
                        name="doctor_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-600 @error('doctor_id') border-red-500 @enderror"
                    >
                        <option value="">Select a doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name }} — {{ $doctor->specialization }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Service Selection -->
                <div class="space-y-2">
                    <label for="service" class="block text-sm font-medium text-gray-700">
                        Select Service
                    </label>
                    <select
                        id="service"
                        name="service_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-600 @error('service_id') border-red-500 @enderror"
                    >
                        <option value="">Select a service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Date -->
                    <div class="space-y-2">
                        <label for="date" class="block text-sm font-medium text-gray-700">
                            Date
                        </label>
                        <input
                            type="date"
                            id="date"
                            name="date"
                            value="{{ old('date') }}"
                            min="{{ \Carbon\Carbon::today()->format('d-m-Y') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('date') border-red-500 @enderror"
                        >
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div class="space-y-2">
                        <label for="start_time" class="block text-sm font-medium text-gray-700">
                            Start Time
                        </label>
                        <input
                            type="time"
                            id="start_time"
                            name="start_time"
                            value="{{ old('start_time') }}"
                            min="{{ \Carbon\Carbon::now()->format('H:i A') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('start_time') border-red-500 @enderror"
                        >
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div class="space-y-2">
                        <label for="end_time" class="block text-sm font-medium text-gray-700">
                            End Time
                        </label>
                        <input
                            type="time"
                            id="end_time"
                            name="end_time"
                            value="{{ old('end_time') }}"
                            min="{{ \Carbon\Carbon::now()->format('H:i A') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('end_time') border-red-500 @enderror"
                        >
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Reason for Visit
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-600 resize-none @error('description') border-red-500 @enderror"
                        placeholder="Please describe your symptoms or reason for consultation..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div>
                    <button
                        type="submit"
                        class="w-full bg-blue-700 text-white py-3 px-4 rounded-lg font-medium shadow-md  hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                    >
                        Book Appointment
                    </button>
                </div>
            </form>
        </div>

        <!-- Doctor Schedules -->
        <div class="bg-white rounded-xl shadow-lg p-8 w-full lg:w-1/4">
            <h2 class="text-xl text-white bg-gradient-to-br from-blue-800 to-blue-900 p-4 rounded-lg font-semibold text-center text-gray-700">Doctor Schedules</h2>
            <div id="schedule-container" class="mt-6 text-blue-800">
                <!-- Schedules will be dynamically loaded here -->
            </div>
            
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                <p class="text-sm text-red-500 mt-2">{{ $error }}</p>
                @endforeach
            @endif 
        </div>
    </div>

     <!-- JavaScript -->
    <script>
    document.getElementById('doctor').addEventListener('change', function () {
        const doctorId = this.value;

        const scheduleContainer = document.getElementById('schedule-container');
        scheduleContainer.innerHTML = ''; // Clear existing schedules

        if (doctorId) {
            fetch(`/get-schedules/${doctorId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let scheduleHTML = '<ul class="list-disc pl-0">';
                        data.schedules.forEach(schedule => {
                            // Display the schedule information
                            scheduleHTML += `<li class="bg-gradient-to-br from-stone-300 to-stone-400 p-4 rounded-lg shadow-md hover:scale-105 hover:shadow-lg transition-all">
                                                <span class="font-bold">${schedule.date} - ${schedule.start_time} - ${schedule.end_time}</span>`;

                            // Display the slots for this schedule
                            if (schedule.slots.length > 0) {
                                scheduleHTML += '<ul class="ml-4">';
                                schedule.slots.forEach(slot => {
                                    const slotStart = slot.start_time;
                                    const slotEnd = slot.end_time;     
                                    const statusClass = slot.is_booked === 1 ? 'text-red-600' : 'text-green-600';
                                    const statusText = slot.is_booked === 1 ? 'Booked' : 'Available';
                                    
                                    scheduleHTML += `<li class="text-sm bg-white p-2 m-2 rounded-md ${statusClass}">
                                                        ${slotStart} - ${slotEnd} - ${statusText}
                                                    </li>`;
                                });
                                scheduleHTML += '</ul>';
                            } else {
                                scheduleHTML += '<p class="text-sm bg-white p-2 m-2 rounded-md text-red-600">All slots are available</p>';
                            }

                            scheduleHTML += '</li>';
                        });
                        scheduleHTML += '</ul>';
                        scheduleContainer.innerHTML = scheduleHTML;
                    } else {
                        scheduleContainer.innerHTML = '<p class="text-sm text-red-600">No schedules available.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching schedules:', error);
                    scheduleContainer.innerHTML = '<p class="text-sm text-red-600">Error loading schedules.</p>';
                });
        }
    });
    </script>    
    

</body>
</html>
