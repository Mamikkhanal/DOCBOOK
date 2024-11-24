<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Appointments</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8 mt-20">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-br from-stone-300 to-stone-400 px-8 py-6">
                @if(Auth::user()->role == 'admin')
                <h1 class="text-2xl font-bold text-blue-800 text-center">
                    All Appointments
                </h1>
                <p class="mt-2 text-stone-800 text-center text-sm">
                    View all scheduled consultations
                </p>
                @else
                <h1 class="text-2xl font-bold text-blue-800 text-center">
                    My Appointments
                </h1>
                <p class="mt-2 text-stone-800 text-center text-sm">
                    View your scheduled consultations
                </p>
                @endif
            </div>

            <!-- Appointment List -->
            <div class="px-8 py-6">
                @if($appointments->isEmpty())
                    <p class="text-center text-gray-600">
                        No appointments scheduled. 
                    </p>
                @else
                    <div class="space-y-6">
                        @foreach($appointments as $appointment)
                        <div class="border rounded-lg p-6 shadow-sm bg-gray-50">
                            <h2 class="inline text-lg font-bold text-gray-800">
                                @if(Auth::user()->role == 'patient')
                                Appointment with Dr. {{ $appointment->doctor->user->name }}
                                @elseif(Auth::user()->role == 'doctor')
                                Appointment with {{ $appointment->patient->user->name }}
                                @else
                                Appointment with Dr. {{ $appointment->doctor->user->name }}</br>
                                Patient: {{ $appointment->patient->user->name }}
                                @endif
                            </h2>
                            @if($appointment->status == 'completed'&& Auth::user()->role == 'patient')
                            <form action="{{ route('review.create') }}" method="POST" class="inline float-right">
                                @csrf
                                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                <button type="submit" class="bg-yellow-200 p-1 rounded-xl text-red-600 text-sm">
                                    Give a review
                                </button>
                                    @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <p class="text-sm text-red-500 mt-2">{{ $error }}</p>
                                    @endforeach
                                    @endif                       
                            </form> 
                            @endif
                            <span class="px-2 py-1 text-xs font-semibold inline-block rounded-full 
                               {{ $appointment->status == 'completed' ? 'bg-green-100 text-green-600' : 
                                ($appointment->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : 
                                ($appointment->status == 'cancelled' ? 'bg-red-100 text-red-600' : 
                                ($appointment->status == 'booked' ? 'bg-blue-100 text-blue-600' :'bg-gray-100 text-gray-600'))) }}">
                                 {{ ucfirst($appointment->status) }}
                                  </span>
                            <p>Appointment ID: {{ $appointment->id }}</p>
                                <p class="text-sm text-gray-600">
                                    Specialization: {{ $appointment->doctor->specialization }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Service: {{ $appointment->service->name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Date: {{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Time: {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Reason: {{ $appointment->description }}
                                </p>
                                
                                <div class="mt-4 flex space-x-4">
                                    
                                    <a href="{{ route('appointment.show', $appointment->id) }}" class="text-blue-800-600 hover:text-blue-800-500">
                                        View
                                    </a>

                                    @if(Auth::user()->role == 'doctor' || Auth::user()->role == 'admin')
                                    <a href="{{ route('appointment.edit', $appointment->id) }}" class="text-blue-800-600 hover:text-blue-800-500">
                                        Edit
                                    </a>
                                    @endif
                            
                                    <form method="POST" action="{{ route('appointment.destroy', $appointment->id) }}" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-500">
                                            Cancel
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
