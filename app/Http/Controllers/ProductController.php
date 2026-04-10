<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
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
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_produit' => 'required|string|max:255',
            'code_produit' => 'required|string|max:100|unique:products,code_produit',
            'prix' => 'required|numeric|min:0',
            'quantite' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fichier_pdf' => 'nullable|mimes:pdf|max:5120',
            'categorie' => 'required|string|max:100',
            'statut' => ['required', Rule::in(['actif', 'inactif'])],
        ], [
            'nom_produit.required' => 'Le nom du produit est obligatoire.',
            'code_produit.required' => 'Le code produit est obligatoire.',
            'code_produit.unique' => 'Ce code produit existe déjà.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.integer' => 'La quantité doit être un nombre entier.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format : jpeg, png, jpg, gif.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
            'fichier_pdf.mimes' => 'Le fichier doit être au format PDF.',
            'fichier_pdf.max' => 'Le fichier PDF ne doit pas dépasser 5 Mo.',
            'categorie.required' => 'La catégorie est obligatoire.',
            'statut.required' => 'Le statut est obligatoire.',
        ]);

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products/images', 'public');
        }

        // Gestion de l'upload du fichier PDF
        if ($request->hasFile('fichier_pdf')) {
            $validated['fichier_pdf'] = $request->file('fichier_pdf')->store('products/pdfs', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produit créé avec succès.');
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
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nom_produit' => 'required|string|max:255',
            'code_produit' => ['required', 'string', 'max:100', Rule::unique('products')->ignore($product->id)],
            'prix' => 'required|numeric|min:0',
            'quantite' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fichier_pdf' => 'nullable|mimes:pdf|max:5120',
            'categorie' => 'required|string|max:100',
            'statut' => ['required', Rule::in(['actif', 'inactif'])],
        ], [
            'nom_produit.required' => 'Le nom du produit est obligatoire.',
            'code_produit.required' => 'Le code produit est obligatoire.',
            'code_produit.unique' => 'Ce code produit existe déjà.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.integer' => 'La quantité doit être un nombre entier.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format : jpeg, png, jpg, gif.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
            'fichier_pdf.mimes' => 'Le fichier doit être au format PDF.',
            'fichier_pdf.max' => 'Le fichier PDF ne doit pas dépasser 5 Mo.',
            'categorie.required' => 'La catégorie est obligatoire.',
            'statut.required' => 'Le statut est obligatoire.',
        ]);

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products/images', 'public');
        }

        // Gestion de l'upload du fichier PDF
        if ($request->hasFile('fichier_pdf')) {
            // Supprimer l'ancien PDF si il existe
            if ($product->fichier_pdf) {
                Storage::disk('public')->delete($product->fichier_pdf);
            }
            $validated['fichier_pdf'] = $request->file('fichier_pdf')->store('products/pdfs', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
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

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}