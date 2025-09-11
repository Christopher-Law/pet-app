@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">All Pets</h1>
            <p class="text-gray-600 text-sm sm:text-base">Manage pet profiles in the system</p>
        </div>
        <a href="{{ route('pets.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
            Add New Pet
        </a>
    </div>

    @if($pets->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pets as $pet)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <div class="p-4 sm:p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $pet->name }}</h3>
                            @if($pet->is_dangerous_animal)
                                <div class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Dangerous
                                </div>
                            @endif
                        </div>
                        
                        <div class="space-y-2 text-sm text-gray-700">
                            <div class="flex justify-between">
                                <span>Type:</span>
                                <span class="font-medium capitalize">{{ $pet->type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Breed:</span>
                                <span class="font-medium">{{ $pet->formatted_breed }}</span>
                            </div>
                            @if($pet->date_of_birth)
                                <div class="flex justify-between">
                                    <span>Age:</span>
                                    <span class="font-medium">{{ $pet->age }}</span>
                                </div>
                            @endif
                            @if($pet->sex)
                                <div class="flex justify-between">
                                    <span>Sex:</span>
                                    <span class="font-medium capitalize">{{ $pet->sex }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6">
                        <a href="{{ route('pets.show', $pet) }}" 
                           class="text-blue-600 hover:text-blue-800 hover:underline font-medium text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded transition-colors duration-200">
                            View Details →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No pets registered</h3>
            <p class="mt-1 text-gray-600">Get started by registering the first pet.</p>
            <div class="mt-6">
                <a href="{{ route('pets.create') }}" 
                   class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add First Pet
                </a>
            </div>
        </div>
    @endif
</div>
@endsection