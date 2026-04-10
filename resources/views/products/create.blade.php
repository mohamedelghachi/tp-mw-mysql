@extends('layout')

@section('title', 'Ajouter un produit')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; align-items: center; margin-bottom: 20px;">
        <a href="{{ route('products.index') }}" style="background: #6c757d; color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px; margin-right: 15px;">
            ← Retour
        </a>
        <h1>Ajouter un produit</h1>
    </div>

    @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>Erreurs de validation :</strong>
            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <!-- Nom du produit -->
            <div>
                <label for="nom_produit" style="display: block; margin-bottom: 5px; font-weight: bold;">Nom du produit *</label>
                <input type="text" id="nom_produit" name="nom_produit" value="{{ old('nom_produit') }}" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;"
                       required>
                @error('nom_produit')
                    <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Code produit -->
            <div>
                <label for="code_produit" style="display: block; margin-bottom: 5px; font-weight: bold;">Code produit *</label>
                <input type="text" id="code_produit" name="code_produit" value="{{ old('code_produit') }}" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;"
                       required>
                @error('code_produit')
                    <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <!-- Prix -->
            <div>
                <label for="prix" style="display: block; margin-bottom: 5px; font-weight: bold;">Prix (€) *</label>
                <input type="number" id="prix" name="prix" value="{{ old('prix') }}" step="0.01" min="0"
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;"
                       required>
                @error('prix')
                    <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Quantité -->
            <div>
                <label for="quantite" style="display: block; margin-bottom: 5px; font-weight: bold;">Quantité *</label>
                <input type="number" id="quantite" name="quantite" value="{{ old('quantite', 0) }}" min="0"
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;"
                       required>
                @error('quantite')
                    <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <!-- Catégorie -->
            <div>
                <label for="categorie" style="display: block; margin-bottom: 5px; font-weight: bold;">Catégorie *</label>
                <select id="categorie" name="categorie" 
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;"
                        required>
                    <option value="">Choisir une catégorie</option>
                    <option value="Informatique" {{ old('categorie') == 'Informatique' ? 'selected' : '' }}>Informatique</option>
                    <option value="Téléphonie" {{ old('categorie') == 'Téléphonie' ? 'selected' : '' }}>Téléphonie</option>
                    <option value="Audio" {{ old('categorie') == 'Audio' ? 'selected' : '' }}>Audio</option>
                    <option value="Accessoires" {{ old('categorie') == 'Accessoires' ? 'selected' : '' }}>Accessoires</option>
                    <option value="Électronique" {{ old('categorie') == 'Électronique' ? 'selected' : '' }}>Électronique</option>
                    <option value="Jeux" {{ old('categorie') == 'Jeux' ? 'selected' : '' }}>Jeux</option>
                </select>
                @error('categorie')
                    <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Statut -->
            <div>
                <label for="statut" style="display: block; margin-bottom: 5px; font-weight: bold;">Statut *</label>
                <select id="statut" name="statut" 
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;"
                        required>
                    <option value="actif" {{ old('statut', 'actif') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
                @error('statut')
                    <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Image -->
        <div style="margin-bottom: 20px;">
            <label for="image" style="display: block; margin-bottom: 5px; font-weight: bold;">Image du produit</label>
            <input type="file" id="image" name="image" accept="image/*"
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
            <small style="color: #6c757d;">Formats acceptés: JPEG, PNG, JPG, GIF. Taille max: 2 Mo</small>
            @error('image')
                <span style="color: #dc3545; font-size: 12px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Fichier PDF -->
        <div style="margin-bottom: 30px;">
            <label for="fichier_pdf" style="display: block; margin-bottom: 5px; font-weight: bold;">Fichier PDF (optionnel)</label>
            <input type="file" id="fichier_pdf" name="fichier_pdf" accept=".pdf"
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
            <small style="color: #6c757d;">Format accepté: PDF. Taille max: 5 Mo</small>
            @error('fichier_pdf')
                <span style="color: #dc3545; font-size: 12px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Boutons -->
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <a href="{{ route('products.index') }}" style="background: #6c757d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;">
                Annuler
            </a>
            <button type="submit" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer;">
                Créer le produit
            </button>
        </div>
    </form>
</div>
@endsection