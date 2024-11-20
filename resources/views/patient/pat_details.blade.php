<html>

<head>
    <title>Patient Details</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="flex flex-col items-center min-h-screen pt-6 sm:justify-center sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('patient.store') }}">
                @csrf
                <div>
                   <label for="age" class="block text-sm font-medium text-gray-700">Age :</label>
                    <input type="number" id="age" name="age" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                 </div>
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('Save') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>