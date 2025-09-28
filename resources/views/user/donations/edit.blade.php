@extends('layouts.user-layout')

@section('title', 'Modifier ma Donation - ' . $donation->book_title)

@section('content')
<div class="donation-edit-page">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-edit"></i> Modifier ma Donation</h1>
            <a href="{{ route('user.donations.show', $donation) }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="donation-form-container">
            <div class="form-header">
                <h2>Modifier "{{ $donation->book_title }}"</h2>
                <p>Vous pouvez modifier votre donation tant qu'elle n'a pas été traitée par notre équipe.</p>
            </div>

            <form action="{{ route('user.donations.update', $donation) }}" method="POST" enctype="multipart/form-data" class="donation-form">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="book_title" class="form-label required">
                            <i class="fas fa-book"></i>
                            Titre du livre
                        </label>
                        <input type="text" 
                               id="book_title" 
                               name="book_title" 
                               class="form-control @error('book_title') error @enderror" 
                               value="{{ old('book_title', $donation->book_title) }}" 
                               placeholder="Ex: Le Petit Prince"
                               required>
                        @error('book_title')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="author" class="form-label required">
                            <i class="fas fa-user-edit"></i>
                            Auteur
                        </label>
                        <input type="text" 
                               id="author" 
                               name="author" 
                               class="form-control @error('author') error @enderror" 
                               value="{{ old('author', $donation->author) }}" 
                               placeholder="Ex: Antoine de Saint-Exupéry"
                               required>
                        @error('author')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="genre" class="form-label">
                            <i class="fas fa-tags"></i>
                            Genre
                        </label>
                        <select id="genre" name="genre" class="form-control @error('genre') error @enderror">
                            <option value="">Sélectionnez un genre</option>
                            <option value="Fiction" {{ old('genre', $donation->genre) == 'Fiction' ? 'selected' : '' }}>Fiction</option>
                            <option value="Non-Fiction" {{ old('genre', $donation->genre) == 'Non-Fiction' ? 'selected' : '' }}>Non-Fiction</option>
                            <option value="Science-Fiction" {{ old('genre', $donation->genre) == 'Science-Fiction' ? 'selected' : '' }}>Science-Fiction</option>
                            <option value="Fantasy" {{ old('genre', $donation->genre) == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                            <option value="Mystery" {{ old('genre', $donation->genre) == 'Mystery' ? 'selected' : '' }}>Mystère</option>
                            <option value="Romance" {{ old('genre', $donation->genre) == 'Romance' ? 'selected' : '' }}>Romance</option>
                            <option value="Thriller" {{ old('genre', $donation->genre) == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                            <option value="Biography" {{ old('genre', $donation->genre) == 'Biography' ? 'selected' : '' }}>Biographie</option>
                            <option value="History" {{ old('genre', $donation->genre) == 'History' ? 'selected' : '' }}>Histoire</option>
                            <option value="Poetry" {{ old('genre', $donation->genre) == 'Poetry' ? 'selected' : '' }}>Poésie</option>
                            <option value="Other" {{ old('genre', $donation->genre) == 'Other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('genre')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="condition" class="form-label required">
                            <i class="fas fa-star"></i>
                            État du livre
                        </label>
                        <select id="condition" name="condition" class="form-control @error('condition') error @enderror" required>
                            <option value="">Sélectionnez l'état</option>
                            <option value="excellent" {{ old('condition', $donation->condition) == 'excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="good" {{ old('condition', $donation->condition) == 'good' ? 'selected' : '' }}>Bon</option>
                            <option value="fair" {{ old('condition', $donation->condition) == 'fair' ? 'selected' : '' }}>Moyen</option>
                            <option value="poor" {{ old('condition', $donation->condition) == 'poor' ? 'selected' : '' }}>Usagé</option>
                        </select>
                        @error('condition')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left"></i>
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              class="form-control @error('description') error @enderror" 
                              rows="4" 
                              placeholder="Décrivez brièvement le contenu du livre, pourquoi vous le donnez, etc...">{{ old('description', $donation->description) }}</textarea>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="book_image" class="form-label">
                        <i class="fas fa-camera"></i>
                        Photo du livre
                    </label>
                    
                    @if($donation->book_image)
                        <div class="current-image">
                            <p class="current-image-label">Image actuelle :</p>
                            <img src="{{ asset('storage/' . $donation->book_image) }}" alt="Image actuelle" class="current-image-preview">
                        </div>
                    @endif
                    
                    <div class="file-input-container">
                        <input type="file" 
                               id="book_image" 
                               name="book_image" 
                               class="form-control file-input @error('book_image') error @enderror" 
                               accept="image/*"
                               onchange="previewImage(this)">
                        <label for="book_image" class="file-input-label">
                            <i class="fas fa-upload"></i>
                            {{ $donation->book_image ? 'Changer l\'image' : 'Choisir une image' }}
                        </label>
                    </div>
                    
                    <div class="image-preview" id="imagePreview" style="display: none;">
                        <img id="previewImg" src="" alt="Aperçu">
                        <button type="button" onclick="removeImage()" class="remove-image">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <small class="form-hint">Formats acceptés: JPG, PNG, GIF. Taille max: 2MB</small>
                    @error('book_image')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i>
                        Sauvegarder les modifications
                    </button>
                    <a href="{{ route('user.donations.show', $donation) }}" class="btn btn-outline btn-lg">
                        <i class="fas fa-times"></i>
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.donation-edit-page {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    color: #2c3e50;
    font-size: 2.5rem;
    margin: 0;
}

.page-header h1 i {
    color: #f39c12;
}

.donation-form-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, #f39c12, #e67e22);
    color: white;
    padding: 2rem;
    text-align: center;
}

.form-header h2 {
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
}

.form-header p {
    margin: 0;
    opacity: 0.9;
}

.donation-form {
    padding: 2rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #2c3e50;
}

.form-label.required::after {
    content: "*";
    color: #e74c3c;
    margin-left: 0.25rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: border-color 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #f39c12;
    box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1);
}

.form-control.error {
    border-color: #e74c3c;
}

.error-message {
    color: #e74c3c;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.current-image {
    margin-bottom: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
}

.current-image-label {
    margin: 0 0 0.5rem 0;
    font-weight: 600;
    color: #2c3e50;
}

.current-image-preview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.file-input-container {
    position: relative;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-input-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem;
    border: 2px dashed #bdc3c7;
    border-radius: 0.5rem;
    background: #f8f9fa;
    color: #7f8c8d;
    cursor: pointer;
    transition: all 0.2s;
}

.file-input-label:hover {
    border-color: #f39c12;
    background: #fff5e6;
    color: #f39c12;
}

.image-preview {
    margin-top: 1rem;
    position: relative;
    display: inline-block;
}

.image-preview img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.remove-image {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-hint {
    color: #7f8c8d;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 1rem;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

.btn-primary {
    background: #f39c12;
    color: white;
}

.btn-primary:hover {
    background: #e67e22;
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    color: #7f8c8d;
    border: 2px solid #bdc3c7;
}

.btn-outline:hover {
    background: #ecf0f1;
    border-color: #95a5a6;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .page-header h1 {
        font-size: 2rem;
    }

    .donation-form-container {
        margin: 0 1rem;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('book_image').value = '';
    document.getElementById('imagePreview').style.display = 'none';
}
</script>
@endsection