@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 sm:py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header with danger indicator -->
        <div class="bg-gray-800 text-white p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold">{{ $pet->name }}</h1>
                    <p class="text-gray-300">{{ ucfirst($pet->type) }} Profile</p>
                </div>
                @if($pet->is_dangerous_animal)
                    <div class="bg-red-500 text-white px-4 py-2 rounded-full flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">Dangerous Animal</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pet Information -->
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Name</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $pet->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Type</label>
                            <p class="mt-1 text-lg text-gray-900 capitalize">{{ $pet->type }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Breed</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $pet->formatted_breed }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Sex</label>
                            <p class="mt-1 text-lg text-gray-900 capitalize">{{ $pet->sex ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Age Information -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">Age Information</h2>
                    
                    @if($pet->date_of_birth)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date of Birth</label>
                            <p class="mt-1 text-lg text-gray-900">{{ \Carbon\Carbon::parse($pet->date_of_birth)->format('F j, Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Age</label>
                            <p class="mt-1 text-lg text-gray-900">
                                @php
                                    $age = \Carbon\Carbon::parse($pet->date_of_birth)->age;
                                    $months = \Carbon\Carbon::parse($pet->date_of_birth)->diffInMonths(now()) % 12;
                                @endphp
                                
                                @if($age > 0)
                                    {{ $age }} {{ $age === 1 ? 'year' : 'years' }}
                                    @if($months > 0)
                                        and {{ $months }} {{ $months === 1 ? 'month' : 'months' }}
                                    @endif
                                @else
                                    {{ $months }} {{ $months === 1 ? 'month' : 'months' }}
                                @endif
                                old
                            </p>
                        </div>
                    @else
                        <div>
                            <p class="text-gray-500 italic">Date of birth not provided</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Danger Warning (if applicable) -->
            @if($pet->is_dangerous_animal)
                <div class="mt-8 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="text-lg font-medium text-red-800 mb-2">Dangerous Animal Notice</h3>
                            <p class="text-red-700">
                                This pet has been classified as a dangerous animal based on their breed. 
                                Please ensure proper safety precautions are taken and check local regulations regarding ownership requirements.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Registration Details -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Registration Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <label class="block font-medium">Registration ID</label>
                        <p class="mt-1">{{ $pet->id }}</p>
                    </div>
                    <div>
                        <label class="block font-medium">Registered On</label>
                        <p class="mt-1">{{ $pet->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4">
                <a href="{{ route('pets.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 sm:px-6 sm:py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 font-medium transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to All Pets
                </a>
                
                <a href="{{ route('pets.create') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Another Pet
                </a>
            </div>
        </div>
    </div>
</div>
@endsection