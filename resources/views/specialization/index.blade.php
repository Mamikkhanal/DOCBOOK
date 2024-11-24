<x-app-layout>

    @include('specialization.create')

    <div class="max-w-xl mx-auto sm:px-6 lg:px-8 my-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex-column justify-center space-y-8 ">
                    <ul class="flex-column justify-center space-y-8">
                        @foreach ($specializations as $specialization)
                            <li class="flex  justify-end space-x-6">
                            <p class="text-md font-semibold inline space-x-20">{{ $specialization->name }}</p>
                                <a href="{{ route('specialization.edit', $specialization->id) }}" class="bg-blue-700 hover:bg-blue-800 text-white py-1 px-4 rounded">
                                    Edit
                                </a>
                                <form action="{{ route('specialization.destroy', $specialization->id) }}" method="POST" class="inline">
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