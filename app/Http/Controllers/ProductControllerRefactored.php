<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductControllerRefactored extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     * 
     * AVANTAGES DU FORMREQUEST :
     * ✅ Validation automatique avant d'entrer dans la méthode
     * ✅ Code plus propre et lisible
     * ✅ Validation réutilisable
     * ✅ Messages d'erreur centralisés
     * ✅ Autorisation gérée automatiquement
     * ✅ Validation métier sophistiquée possible
     */
    public function store(StoreProductRequest $request)
    {
        // À ce stade, toute la validation est déjà passée !
        // Plus besoin de $request->validate() 
        $validated = $request->validated();

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products/images', 'public');
        }

        // Gestion de l'upload du fichier PDF
        if ($request->hasFile('fichier_pdf')) {
            $validated['fichier_pdf'] = $request->file('fichier_pdf')->store('products/pdfs', 'public');
        }

        $product = Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', "Produit '{$product->nom_produit}' créé avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     * 
     * AVANTAGES DU FORMREQUEST POUR UPDATE :
     * ✅ Validation sophistiquée avec règles conditionnelles
     * ✅ Gestion automatique des règles unique avec ignore
     * ✅ Validation métier complexe (stock vs statut)
     * ✅ Gestion des suppressions de fichiers
     * ✅ Code business logic séparé de la validation
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Utiliser la méthode personnalisée qui gère les suppressions de fichiers
        $validated = $request->validatedWithFiles();

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products/images', 'public');
        } elseif (isset($validated['image']) && is_null($validated['image'])) {
            // Suppression demandée
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
        }

        // Gestion de l'upload du fichier PDF
        if ($request->hasFile('fichier_pdf')) {
            // Supprimer l'ancien PDF s'il existe
            if ($product->fichier_pdf) {
                Storage::disk('public')->delete($product->fichier_pdf);
            }
            $validated['fichier_pdf'] = $request->file('fichier_pdf')->store('products/pdfs', 'public');
        } elseif (isset($validated['fichier_pdf']) && is_null($validated['fichier_pdf'])) {
            // Suppression demandée
            if ($product->fichier_pdf) {
                Storage::disk('public')->delete($product->fichier_pdf);
            }
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', "Produit '{$product->nom_produit}' mis à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     * 
     * Vous pourriez aussi créer un DeleteProductRequest pour gerer l'autorisation de suppression !
     */
    public function destroy(Product $product)
    {
        // Supprimer les fichiers associés
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        if ($product->fichier_pdf) {
            Storage::disk('public')->delete($product->fichier_pdf);
        }

        $productName = $product->nom_produit;
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', "Produit '{$productName}' supprimé avec succès.");
    }
}