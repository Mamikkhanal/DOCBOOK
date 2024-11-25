<x-app-layout>

    @include('specialization.create')

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 my-3">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex-column justify-center space-y-8 ">
                    <table class="table-auto w-full border-collapse border border-gray-300 text-left">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-gray-300 px-4 py-2">Specialization Name</th>
                                <th class="border border-gray-300 px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($specializations as $specialization)
                                <tr class="hover:bg-gray-100">
                                    <td class="border border-gray-300 px-4 py-2">{{ $specialization->name }}</td>
                                    <td class="border border-gray-300 px-4 py-2 flex space-x-2">
                                        <a href="{{ route('specialization.edit', $specialization->id) }}" 
                                           class="bg-blue-700 hover:bg-blue-800 text-white py-1 px-4 rounded">
                                            Edit
                                        </a>
                                        <form action="{{ route('specialization.destroy', $specialization->id) }}" method="POST" class="inline">
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