@extends('layout')

@section('title', 'Liste des produits')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Liste des produits</h1>
        <a href="{{ route('products.create') }}" style="background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
            Ajouter un produit
        </a>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($products->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Image</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Nom du produit</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Code</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Prix</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Quantité</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Catégorie</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Statut</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            @if($product->aUneImage())
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Image produit" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                            @else
                                <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                    📷
                                </div>
                            @endif
                        </td>
                        <td style="padding: 12px; font-weight: bold;">{{ $product->nom_produit }}</td>
                        <td style="padding: 12px; font-family: monospace;">{{ $product->code_produit }}</td>
                        <td style="padding: 12px; font-weight: bold; color: #28a745;">{{ $product->prix_formate }}</td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 8px; border-radius: 3px; font-size: 12px; 
                                {{ $product->enStock() ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;' }}">
                                {{ $product->quantite }}
                                @if($product->quantite == 0) (Rupture) @endif
                            </span>
                        </td>
                        <td style="padding: 12px;">{{ $product->categorie }}</td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 8px; border-radius: 3px; font-size: 12px; 
                                {{ $product->statut == 'actif' ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;' }}">
                                {{ ucfirst($product->statut) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('products.show', $product) }}" style="background: #17a2b8; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px;">
                                    Voir
                                </a>
                                <a href="{{ route('products.edit', $product) }}" style="background: #ffc107; color: #212529; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px;">
                                    Modifier
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $products->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 5px;">
            <h3 style="color: #6c757d;">Aucun produit trouvé</h3>
            <p style="color: #6c757d; margin-bottom: 20px;">Commencez par ajouter votre premier produit.</p>
            <a href="{{ route('products.create') }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                Ajouter un produit
            </a>
        </div>
    @endif
</div>
@endsection