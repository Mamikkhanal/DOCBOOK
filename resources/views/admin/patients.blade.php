<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="bg-gradient-to-br from-stone-300 to-stone-400 p-4 text-blue-800 rounded-lg text-2xl font-bold mb-6 text-center">Patients</h1> 
        
        <!-- patients List -->
        <table class="min-w-full border-collapse border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-stone-200">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Phone</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Age</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patients as $patient)
                    <tr class="hover:bg-stone-50 text-blue-800">
                        <td class="border border-gray-300 px-4 py-2">{{ $patient->user->name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $patient->user->email }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $patient->user->phone }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $patient->age}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                            No patients found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
