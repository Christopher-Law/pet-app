@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8" x-data="petForm()">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Register Your Pet</h1>
            <p class="text-gray-600">Tell us about your furry friend</p>
        </div>

        <form action="{{ route('pets.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Pet's Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Pet's Name *</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pet Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pet Type *</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" 
                               name="type" 
                               value="dog" 
                               x-model="formData.type"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-900">Dog</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="type" 
                               value="cat" 
                               x-model="formData.type"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-900">Cat</span>
                    </label>
                </div>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Breed Selection -->
            <div x-show="formData.type">
                <label for="breed" class="block text-sm font-medium text-gray-700 mb-2">Breed</label>
                <select id="breed" 
                        name="breed" 
                        x-model="formData.breed"
                        @change="checkDangerousBreed"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select a breed</option>
                    <template x-for="breed in getBreeds()" :key="breed">
                        <option :value="breed" x-text="breed"></option>
                    </template>
                    <option value="dont_know">I don't know</option>
                    <option value="mixed">It's a mix</option>
                </select>
                @error('breed')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <!-- Dangerous Animal Tooltip -->
                <div x-show="showDangerWarning" 
                     x-transition
                     class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-red-800" x-text="dangerWarningText"></span>
                    </div>
                </div>
            </div>

            <!-- Age Section -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Do you know their date of birth?</label>
                <div class="flex space-x-4 mb-4">
                    <label class="flex items-center">
                        <input type="radio" 
                               value="no" 
                               x-model="formData.knowsBirthDate"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-900">No - Approximate Age</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               value="yes" 
                               x-model="formData.knowsBirthDate"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-900">Yes - Date of Birth</span>
                    </label>
                </div>

                <!-- Approximate Age -->
                <div x-show="formData.knowsBirthDate === 'no'">
                    <label for="approximate_age" class="block text-sm font-medium text-gray-700 mb-2">Approximate Age (years)</label>
                    <select id="approximate_age" 
                            x-model="formData.approximateAge"
                            @change="calculateBirthDate"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select age</option>
                        <template x-for="age in Array.from({length: 20}, (_, i) => i + 1)" :key="age">
                            <option :value="age" x-text="age + (age === 1 ? ' year' : ' years')"></option>
                        </template>
                    </select>
                </div>

                <!-- Date of Birth -->
                <div x-show="formData.knowsBirthDate === 'yes'">
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" 
                           id="date_of_birth" 
                           name="date_of_birth"
                           x-model="formData.dateOfBirth"
                           :value="formData.dateOfBirth"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Hidden field for calculated birth date -->
                <input type="hidden" 
                       name="date_of_birth" 
                       x-show="formData.knowsBirthDate === 'no'"
                       :value="formData.calculatedBirthDate">

                @error('date_of_birth')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sex -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" 
                               name="sex" 
                               value="male" 
                               x-model="formData.sex"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-900">Male</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="sex" 
                               value="female" 
                               x-model="formData.sex"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-900">Female</span>
                    </label>
                </div>
                @error('sex')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hidden dangerous animal field -->
            <input type="hidden" name="is_dangerous_animal" :value="isDangerousAnimal ? 1 : 0">

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                    Save Pet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function petForm() {
    return {
        formData: {
            type: '{{ old('type') }}',
            breed: '{{ old('breed') }}',
            knowsBirthDate: 'no',
            approximateAge: '',
            dateOfBirth: '{{ old('date_of_birth') }}',
            calculatedBirthDate: '',
            sex: '{{ old('sex') }}'
        },
        showDangerWarning: false,
        dangerWarningText: '',
        isDangerousAnimal: false,

        breeds: {
            dog: [
                'Pitbull',
                'Mastiff',
                'German Shepherd',
                'Golden Retriever',
                'Labrador Retriever',
                'Bulldog',
                'Rottweiler',
                'Beagle',
                'Poodle',
                'Siberian Husky'
            ],
            cat: [
                'Persian',
                'Maine Coon',
                'British Shorthair',
                'Ragdoll',
                'Siamese',
                'American Shorthair',
                'Abyssinian',
                'Russian Blue',
                'Scottish Fold',
                'Bengal'
            ]
        },

        dangerousBreeds: ['Pitbull', 'Mastiff'],

        getBreeds() {
            return this.formData.type ? this.breeds[this.formData.type] : [];
        },

        checkDangerousBreed() {
            const breed = this.formData.breed;
            if (this.dangerousBreeds.includes(breed)) {
                this.showDangerWarning = true;
                this.dangerWarningText = `${breed} is considered dangerous.`;
                this.isDangerousAnimal = true;
            } else {
                this.showDangerWarning = false;
                this.dangerWarningText = '';
                this.isDangerousAnimal = false;
            }
        },

        calculateBirthDate() {
            if (this.formData.approximateAge) {
                const currentYear = new Date().getFullYear();
                const birthYear = currentYear - parseInt(this.formData.approximateAge);
                this.formData.calculatedBirthDate = `${birthYear}-01-01`;
            }
        }
    }
}
</script>
@endsection