@extends('layouts.user-layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Planifier la remise du livre</h1>
            
            <!-- Informations sur le livre -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Livre à remettre :</h3>
                <p class="text-gray-600"><strong>Titre :</strong> {{ $donation->book_title }}</p>
                <p class="text-gray-600"><strong>Auteur :</strong> {{ $donation->author }}</p>
                @if($donation->description)
                    <p class="text-gray-600"><strong>Description :</strong> {{ Str::limit($donation->description, 100) }}</p>
                @endif
            </div>

            <!-- Messages d'erreur/succès -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Formulaire de planification -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('remise.store', $donation->id) }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date du rendez-vous -->
                    <div class="col-span-2">
                        <label for="date_rendez_vous" class="block text-sm font-medium text-gray-700 mb-2">
                            Date et heure du rendez-vous *
                        </label>
                        <input 
                            type="datetime-local" 
                            id="date_rendez_vous" 
                            name="date_rendez_vous"
                            value="{{ old('date_rendez_vous') }}"
                            min="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_rendez_vous') border-red-500 @enderror"
                            required
                        >
                        @error('date_rendez_vous')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lieu -->
                    <div class="col-span-2">
                        <label for="lieu" class="block text-sm font-medium text-gray-700 mb-2">
                            Lieu de remise *
                        </label>
                        <input 
                            type="text" 
                            id="lieu" 
                            name="lieu"
                            value="{{ old('lieu') }}"
                            placeholder="Ex: Bibliothèque centrale, Café du centre-ville..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('lieu') border-red-500 @enderror"
                            required
                        >
                        @error('lieu')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Administrateur responsable -->
                    <div class="col-span-2">
                        <label for="admin_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Administrateur responsable *
                        </label>
                        <select 
                            id="admin_id" 
                            name="admin_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('admin_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Sélectionner un administrateur</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('admin_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">Important :</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• L'administrateur sélectionné sera notifié de votre demande</li>
                        <li>• Vous recevrez une confirmation une fois le rendez-vous validé</li>
                        <li>• Assurez-vous d'être disponible à la date et heure choisies</li>
                    </ul>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('user.donations.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition duration-200">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-md transition duration-200 font-semibold">
                        Planifier la remise
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Définir la date minimale à maintenant + 2 heures
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date_rendez_vous');
        const now = new Date();
        now.setHours(now.getHours() + 2);
        const minDateTime = now.toISOString().slice(0, 16);
        dateInput.setAttribute('min', minDateTime);
    });
</script>
@endsection