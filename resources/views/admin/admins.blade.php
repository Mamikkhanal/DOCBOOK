<x-app-layout>
    <form method="POST" action="{{ route('register') }}" class="max-w-4xl mx-auto p-6">
        @csrf
        <div class="text-center mb-2">
            <h2 class="bg-gradient-to-br from-stone-300 to-stone-400 text-blue-800 rounded-lg p-4 text-2xl font-bold">Add Admin</h2>
            <p class="text-gray-500">Fill in the details below to add a new admin.</p>
        </div>

        <div class="space-y-2">
            <!-- Name Input -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    placeholder="Enter Name"
                    value="{{ old('name') }}"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                >
            </div>

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    placeholder="Enter Email"
                    value="{{ old('email') }}"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                >
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Enter Password"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                >
            </div>

            <!-- Phone Input -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input
                    id="phone"  
                    type="text"
                    name="phone"
                    placeholder="Enter Phone"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                >
            </div>

            <!-- Hidden Role Field -->
            <input type="hidden" name="role" value="admin">

            <!-- Submit Button -->
            <div class="text-center">
                <x-primary-button>
                    {{ __('Add Admin') }}
                </x-primary-button>
            </div>
        </div>
    </form>

    <div class="max-w-4xl mx-auto p-6">
        <h1 class="bg-gradient-to-br from-stone-300 to-stone-400 p-4 text-blue-800 rounded-lg text-2xl font-bold mb-6 text-center">Admins</h1> 
        
        <!-- Admins List -->
        <table class="min-w-full border-collapse border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-stone-200">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Phone</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($admins as $admin)
                    <tr class="hover:bg-stone-50">
                        <td class="border border-gray-300 px-4 py-2">{{ $admin->name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $admin->email }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $admin->phone }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <form action="{{ route('adminsDelete', $admin->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 font-bold">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                            No admins found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
