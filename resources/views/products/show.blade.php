@extends('layout')

@section('title', $product->nom_produit)

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div style="display: flex; align-items: center;">
            <a href="{{ route('products.index') }}" style="background: #6c757d; color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px; margin-right: 15px;">
                ← Retour
            </a>
            <h1 style="margin: 0;">{{ $product->nom_produit }}</h1>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('products.edit', $product) }}" style="background: #ffc107; color: #212529; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                ✏️ Modifier
            </a>
            <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: #dc3545; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
                    🗑️ Supprimer
                </button>
            </form>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        <!-- Colonne gauche - Image et fichiers -->
        <div>
            <!-- Image -->
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h3 style="margin-top: 0; margin-bottom: 15px;">Image du produit</h3>
                @if($product->aUneImage())
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nom_produit }}" 
                         style="width: 100%; max-width: 300px; height: auto; border-radius: 10px; border: 1px solid #ddd;">
                @else
                    <div style="width: 100%; height: 200px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d; border: 1px dashed #dee2e6;">
                        <div style="text-align: center;">
                            <div style="font-size: 48px; margin-bottom: 10px;">📷</div>
                            <p>Aucune image</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Fichier PDF -->
            @if($product->aUnFichierPdf())
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="margin-top: 0; margin-bottom: 15px;">Documentation</h3>
                <a href="{{ asset('storage/' . $product->fichier_pdf) }}" target="_blank" 
                   style="display: inline-block; background: #17a2b8; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; width: 100%; text-align: center; box-sizing: border-box;">
                    📄 Télécharger le PDF
                </a>
            </div>
            @endif
        </div>

        <!-- Colonne droite - Informations -->
        <div>
            <!-- Informations générales -->
            <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h3 style="margin-top: 0; margin-bottom: 20px; color: #495057;">Informations générales</h3>
                
                <div style="display: grid; gap: 15px;">
                    <div style="display: grid; grid-template-columns: 150px 1fr; align-items: center; padding: 10px; border-radius: 5px; background: #f8f9fa;">
                        <strong style="color: #495057;">Code produit :</strong>
                        <span style="font-family: monospace; background: white; padding: 4px 8px; border-radius: 3px; border: 1px solid #dee2e6;">{{ $product->code_produit }}</span>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 150px 1fr; align-items: center; padding: 10px; border-radius: 5px;">
                        <strong style="color: #495057;">Prix :</strong>
                        <span style="font-size: 24px; font-weight: bold; color: #28a745;">{{ $product->prix_formate }}</span>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 150px 1fr; align-items: center; padding: 10px; border-radius: 5px; background: #f8f9fa;">
                        <strong style="color: #495057;">Catégorie :</strong>
                        <span style="background: #007bff; color: white; padding: 4px 12px; border-radius: 15px; font-size: 14px; display: inline-block;">{{ $product->categorie }}</span>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 150px 1fr; align-items: center; padding: 10px; border-radius: 5px;">
                        <strong style="color: #495057;">Statut :</strong>
                        <span style="padding: 6px 12px; border-radius: 15px; font-size: 14px; font-weight: bold; 
                            {{ $product->statut == 'actif' ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;' }}">
                            {{ $product->statut == 'actif' ? '✅ Actif' : '❌ Inactif' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Gestion du stock -->
            <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h3 style="margin-top: 0; margin-bottom: 20px; color: #495057;">Gestion du stock</h3>
                
                <div style="display: grid; gap: 15px;">
                    <div style="display: grid; grid-template-columns: 150px 1fr; align-items: center; padding: 15px; border-radius: 8px; 
                        {{ $product->enStock() ? 'background: #d4edda;' : 'background: #f8d7da;' }}">
                        <strong style="color: #495057;">Quantité :</strong>
                        <div>
                            <span style="font-size: 32px; font-weight: bold; 
                                {{ $product->enStock() ? 'color: #155724;' : 'color: #721c24;' }}">
                                {{ $product->quantite }}
                            </span>
                            @if($product->quantite == 0)
                                <span style="display: block; color: #721c24; font-weight: bold; font-size: 14px;">⚠️ Produit en rupture de stock</span>
                            @elseif($product->quantite <= 5)
                                <span style="display: block; color: #856404; font-weight: bold; font-size: 14px;">⚠️ Stock faible</span>
                            @else
                                <span style="display: block; color: #155724; font-size: 14px;">✅ En stock</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Métadonnées -->
            <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="margin-top: 0; margin-bottom: 20px; color: #495057;">Métadonnées</h3>
                
                <div style="display: grid; gap: 10px; font-size: 14px; color: #6c757d;">
                    <div style="display: grid; grid-template-columns: 120px 1fr;">
                        <strong>Créé le :</strong>
                        <span>{{ $product->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 120px 1fr;">
                        <strong>Modifié le :</strong>
                        <span>{{ $product->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 120px 1fr;">
                        <strong>ID :</strong>
                        <span>#{{ $product->id }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection