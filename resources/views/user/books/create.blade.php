@extends('layouts.user-layout')

@section('title', 'Ajouter un Livre - Social Book Network')
@section('styles')
    <style>
        .create-book-page {
            padding: 40px 0;
            background: #f8f9fa;
            min-height: 80vh;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: 2.2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .page-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-label .required {
            color: #e74c3c;
        }

        .form-control {
            padding: 12px 15px;
            border: 2px solid var(--gray-light);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }

        .file-upload {
            position: relative;
            border: 2px dashed var(--gray-light);
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload:hover {
            border-color: var(--primary-color);
            background: var(--light-color);
        }

        .file-upload i {
            font-size: 2.5rem;
            color: var(--text-light);
            margin-bottom: 15px;
        }

        .file-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-info {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 8px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 10px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-weight: 500;
            color: var(--text-color);
        }

        .checkbox-label input[type="checkbox"] {
            display: none;
        }

        .checkbox-custom {
            width: 20px;
            height: 20px;
            border: 2px solid var(--gray-light);
            border-radius: 4px;
            position: relative;
            transition: all 0.3s ease;
        }

        .checkbox-label input[type="checkbox"]:checked + .checkbox-custom {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .checkbox-label input[type="checkbox"]:checked + .checkbox-custom::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 12px;
            font-weight: bold;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 25px;
            border-top: 1px solid var(--gray-light);
        }

        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .btn-secondary {
            background: var(--light-color);
            color: var(--text-color);
        }

        .btn-secondary:hover {
            background: var(--gray-light);
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
            font-weight: 500;
        }

        .help-text {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-top: 5px;
        }

        /* Preview de l'image */
        .image-preview {
            margin-top: 15px;
            text-align: center;
        }

        .image-preview img {
            max-width: 200px;
            max-height: 250px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-container {
                padding: 25px;
                margin: 0 15px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="create-book-page">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>📖 Ajouter un Livre</h1>
                <p>Partagez un nouveau livre avec la communauté</p>
            </div>

            <!-- Form Container -->
            <div class="form-container">
                <form action="{{ route('user.books.store') }}" method="POST" enctype="multipart/form-data" id="bookForm">
                    @csrf

                    <div class="form-grid">
                        <!-- Titre du livre -->
                        <div class="form-group full-width">
                            <label for="title" class="form-label">
                                Titre du livre <span class="required">*</span>
                            </label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ old('title') }}" required maxlength="255"
                                   placeholder="Ex: L'Étranger">
                            @error('title')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Auteur -->
                        <div class="form-group">
                            <label for="author_name" class="form-label">
                                Auteur <span class="required">*</span>
                            </label>
                            <input type="text" name="author_name" id="author_name" class="form-control"
                                   value="{{ old('author_name') }}" required maxlength="255"
                                   placeholder="Ex: Albert Camus">
                            @error('author_name')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- ISBN -->
                        <div class="form-group">
                            <label for="isbn" class="form-label">
                                ISBN <span class="required">*</span>
                            </label>
                            <input type="text" name="isbn" id="isbn" class="form-control"
                                   value="{{ old('isbn') }}" required maxlength="255"
                                   placeholder="Ex: 978-2070360024">
                            @error('isbn')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Catégorie -->
                        <div class="form-group">
                            <label for="category_id" class="form-label">
                                Catégorie <span class="required">*</span>
                            </label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Couverture du livre -->
                        <div class="form-group full-width">
                            <label class="form-label">Couverture du livre</label>
                            <div class="file-upload" id="fileUpload">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div>
                                    <strong>Cliquez pour télécharger une image</strong>
                                    <div class="file-info">Formats: JPEG, PNG, JPG, GIF (Max: 2MB)</div>
                                </div>
                                <input type="file" name="book_cover" id="book_cover" accept="image/*">
                            </div>
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImage" src="#" alt="Aperçu">
                            </div>
                            @error('book_cover')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Synopsis -->
                        <div class="form-group full-width">
                            <label for="synopsis" class="form-label">Synopsis</label>
                            <textarea name="synopsis" id="synopsis" class="form-control"
                                      rows="6" maxlength="1000"
                                      placeholder="Décrivez brièvement le livre...">{{ old('synopsis') }}</textarea>
                            <div class="help-text">Maximum 1000 caractères</div>
                            @error('synopsis')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Paramètres -->
                        <div class="form-group full-width">
                            <label class="form-label">Paramètres du livre</label>
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="shareable" value="1" {{ old('shareable') ? 'checked' : 'checked' }}>
                                    <span class="checkbox-custom"></span>
                                    Rendre ce livre visible par la communauté
                                </label>
                            </div>
                            <div class="help-text">
                                Lorsque cette option est activée, les autres membres pourront voir votre livre dans la bibliothèque publique.
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('user.books.my-books') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Ajouter le livre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preview de l'image
            const bookCoverInput = document.getElementById('book_cover');
            const imagePreview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');
            const fileUpload = document.getElementById('fileUpload');

            bookCoverInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();

                    reader.addEventListener('load', function() {
                        previewImage.setAttribute('src', this.result);
                        imagePreview.style.display = 'block';
                        fileUpload.style.display = 'none';
                    });

                    reader.readAsDataURL(file);
                }
            });

            // Réinitialiser le preview
            document.getElementById('bookForm').addEventListener('reset', function() {
                imagePreview.style.display = 'none';
                fileUpload.style.display = 'block';
            });
        });
    </script>
@endsection
