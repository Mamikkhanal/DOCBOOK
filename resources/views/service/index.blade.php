<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-8">
        <h1 class=" bg-gradient-to-br from-stone-300 to-stone-400 p-4 text-blue-800 rounded-lg text-2xl font-bold mb-6 text-center">Services</h1>
        <div class="m-4 text-center">
            <a href="{{ route('service.create') }}" class="bg-blue-700 hover:bg-blue-800 text-white font-bold  py-2 px-8 rounded">
                Add Service
            </a>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex-column justify-center space-y-8 ">
                    <table class="table-auto w-full border-collapse border border-gray-300 text-left">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-gray-300 px-4 py-2">Name</th>
                                <th class="border border-gray-300 px-4 py-2">Description</th>
                                <th class="border border-gray-300 px-4 py-2">Category</th>
                                <th class="border border-gray-300 px-4 py-2">Price</th>
                                <th class="border border-gray-300 px-4 py-2">Available</th>
                                <th class="border border-gray-300 px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr class="hover:bg-gray-100">
                                    <td class="border border-gray-300 px-4 py-2 text-blue-800">{{ $service->name }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-blue-800 ">{{ $service->description }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-blue-800">{{ $service->category }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-blue-800">{{ $service->price }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-blue-800">{{ $service->is_available ? 'Yes' : 'No' }}</td>
                                    <td class="border border-gray-300 px-4 py-2 flex space-x-2">
                                        <a href="{{ route('service.edit', $service->id) }}" 
                                           class="bg-blue-700 hover:bg-blue-800 text-white py-1 px-4 rounded">
                                            Edit
                                        </a>
                                        <form action="{{ route('service.destroy', $service->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-700 hover:bg-red-800 text-white py-1 px-4 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>        
            </div>
        </div>
    </div>
</x-app-layout>