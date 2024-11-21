<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Appointment</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg mx-auto">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-6">
                <h1 class="text-2xl font-bold text-white text-center">
                    Edit Appointment
                </h1>
                <p class="mt-2 text-indigo-100 text-center text-sm">
                    Modify your appointment details
                </p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('appointment.update', $appointment->id) }}" class="px-8 py-6 space-y-6">
                @csrf
                @method('PUT')

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
                            <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name }} -- {{ $doctor->specialization }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Service Selection -->
                <div class="space-y-2">
                    <label for='service' class="block text-sm font-medium text-gray-700">
                        Select Service
                    </label>
                    <select
                        id="service"
                        name="service_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-600 @error('service_id') border-red-500 @enderror"
                    >
                        <option value="">Select a service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ $appointment->service_id == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Date -->
                    <div class="space-y-2">
                        <label for="date" class="block text-sm font-medium text-gray-700">
                            Date
                        </label>
                        <input
                            type="date"
                            id="date"
                            name="date"
                            value="{{ old('date', $appointment->date) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('date') border-red-500 @enderror"
                        >
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <br/>

                    <!-- Start Time -->
                    <div class="space-y-2">
                        <label for="starttime" class="block text-sm font-medium text-gray-700">
                            Start Time
                        </label>
                        <input
                            type="time"
                            id="start_time"
                            name="start_time"
                            value="{{ old('start_time', $appointment->start_time) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('start_time') border-red-500 @enderror"
                        >
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div class="space-y-2">
                        <label for="endtime" class="block text-sm font-medium text-gray-700">
                            End Time
                        </label>
                        <input
                            type="time"
                            id="end_time"
                            name="end_time"
                            value="{{ old('end_time', $appointment->end_time) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('end_time') border-red-500 @enderror"
                        >
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">
                        Status
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-600 @error('status') border-red-500 @enderror"
                    >
                        <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="booked" {{ $appointment->status == 'booked' ? 'selected' : '' }}>Booked</option>
                        <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rescheduled" {{ $appointment->status == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                    </select>
                
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                

                <!-- Reason for Visit -->
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
                    >{{ old('description', $appointment->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-3 px-4 rounded-lg font-medium shadow-md hover:from-indigo-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                    >
                        Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
