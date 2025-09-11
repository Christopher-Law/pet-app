@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">
                Pet Management
                <span class="text-blue-600">
                    System
                </span>
            </h1>
            
            <div class="space-y-4">
                <a href="{{ route('pets.create') }}" 
                   class="block w-full inline-flex items-center justify-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Pet
                </a>
                
                <a href="{{ route('pets.index') }}" 
                   class="block w-full inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 font-semibold rounded-xl shadow-lg hover:bg-gray-50 border border-gray-200 hover:border-gray-300 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    View All Pets
                </a>
            </div>
        </div>
    </div>
</div>
@endsection