<x-app-layout>
    <div class="m-4 text-right">
        <a href="{{ route('service.create') }}" class="bg-blue-700 hover:bg-blue-800 text-white font-bold  py-1 px-4 rounded">
            Add Service
        </a>
    </div>
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8 my-8">
        <h1 class=" bg-gradient-to-br from-stone-300 to-stone-400 p-4 text-blue-800 rounded-lg text-2xl font-bold mb-6 text-center">Services</h1>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex-column justify-center space-y-8 ">
                    <ul class="flex-column justify-center space-y-8">
                        @foreach ($services as $service)
                            <li class="flex  justify-end space-x-6">
                            <p class="text-md font-semibold inline space-x-20">{{ $service->name }}</p>
                                <a href="{{ route('service.edit', $service->id) }}" class="bg-blue-700 hover:bg-blue-800 text-white  py-1 px-4 rounded">
                                    Edit
                                </a>
                                <form action="{{ route('service.destroy', $service->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-700 hover:bg-red-800 text-white  py-1 px-4 rounded">
                                        Delete
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>        
            </div>
        </div>
    </div>
</x-app-layout>