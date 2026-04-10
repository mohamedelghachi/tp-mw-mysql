@extends('layout')

@section('title', 'Modifier le produit')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; align-items: center; margin-bottom: 20px;">
        <a href="{{ route('products.index') }}" style="background: #6c757d; color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px; margin-right: 15px;">
            ← Retour
        </a>
        <h1>Modifier le produit : {{ $product->nom_produit }}</h1>
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

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <!-- Nom du produit -->
            <div>
                <label for="nom_produit" style="display: block; margin-bottom: 5px; font-weight: bold;">Nom du produit *</label>
                <input type="text" id="nom_produit" name="nom_produit" value="{{ old('nom_produit', $product->nom_produit) }}" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;"
                       required>
                @error('nom_produit')
                    <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Code produit -->
            <div>
                <label for="code_produit" style="display: block; margin-bottom: 5px; font-weight: bold;">Code produit *</label>
                <input type="text" id="code_produit" name="code_produit" value="{{ old('code_produit', $product->code_produit) }}" 
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
                <input type="number" id="prix" name="prix" value="{{ old('prix', $product->prix) }}" step="0.01" min="0"
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;"
                       required>
                @error('prix')
                    <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Quantité -->
            <div>
                <label for="quantite" style="display: block; margin-bottom: 5px; font-weight: bold;">Quantité *</label>
                <input type="number" id="quantite" name="quantite" value="{{ old('quantite', $product->quantite) }}" min="0"
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
                    <option value="Informatique" {{ old('categorie', $product->categorie) == 'Informatique' ? 'selected' : '' }}>Informatique</option>
                    <option value="Téléphonie" {{ old('categorie', $product->categorie) == 'Téléphonie' ? 'selected' : '' }}>Téléphonie</option>
                    <option value="Audio" {{ old('categorie', $product->categorie) == 'Audio' ? 'selected' : '' }}>Audio</option>
                    <option value="Accessoires" {{ old('categorie', $product->categorie) == 'Accessoires' ? 'selected' : '' }}>Accessoires</option>
                    <option value="Électronique" {{ old('categorie', $product->categorie) == 'Électronique' ? 'selected' : '' }}>Électronique</option>
                    <option value="Jeux" {{ old('categorie', $product->categorie) == 'Jeux' ? 'selected' : '' }}>Jeux</option>
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
                    <option value="actif" {{ old('statut', $product->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ old('statut', $product->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
                @error('statut')
                    <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Image actuelle et nouveau upload -->
        <div style="margin-bottom: 20px;">
            <label for="image" style="display: block; margin-bottom: 5px; font-weight: bold;">Image du produit</label>
            
            @if($product->aUneImage())
                <div style="margin-bottom: 10px;">
                    <p style="margin-bottom: 5px; color: #6c757d; font-size: 14px;">Image actuelle :</p>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Image actuelle" 
                         style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                </div>
            @endif
            
            <input type="file" id="image" name="image" accept="image/*"
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
            <small style="color: #6c757d;">Formats acceptés: JPEG, PNG, JPG, GIF. Taille max: 2 Mo. Laissez vide pour conserver l'image actuelle.</small>
            @error('image')
                <span style="color: #dc3545; font-size: 12px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Fichier PDF actuel et nouveau upload -->
        <div style="margin-bottom: 30px;">
            <label for="fichier_pdf" style="display: block; margin-bottom: 5px; font-weight: bold;">Fichier PDF (optionnel)</label>
            
            @if($product->aUnFichierPdf())
                <div style="margin-bottom: 10px;">
                    <p style="margin-bottom: 5px; color: #6c757d; font-size: 14px;">Fichier PDF actuel :</p>
                    <a href="{{ asset('storage/' . $product->fichier_pdf) }}" target="_blank" 
                       style="background: #17a2b8; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px;">
                        📄 Voir le PDF
                    </a>
                </div>
            @endif
            
            <input type="file" id="fichier_pdf" name="fichier_pdf" accept=".pdf"
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
            <small style="color: #6c757d;">Format accepté: PDF. Taille max: 5 Mo. Laissez vide pour conserver le fichier actuel.</small>
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
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection