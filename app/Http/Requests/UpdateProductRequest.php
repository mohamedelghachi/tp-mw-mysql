<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Exemple d'autorisation sophistiquée pour la mise à jour
        $product = $this->route('product');
        
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return false;
        }

        // Exemple : autoriser seulement certains rôles ou le propriétaire
        // return auth()->user()->hasRole('admin') || auth()->user()->id === $product->created_by;
        
        // Pour le moment, autoriser tous les utilisateurs connectés
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Récupérer l'ID du produit à partir de la route
        $productId = $this->route('product')->id;

        return [
            'nom_produit' => ['sometimes', 'required', 'string', 'max:255', 'min:3'],
            'code_produit' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'code_produit')->ignore($productId),
                'regex:/^[A-Z]{2,3}[0-9]{3,5}$/'
            ],
            'prix' => ['sometimes', 'required', 'numeric', 'min:0.01', 'max:999999.99'],
            'quantite' => ['sometimes', 'required', 'integer', 'min:0', 'max:10000'],
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'fichier_pdf' => ['sometimes', 'nullable', 'mimes:pdf', 'max:5120'],
            'categorie' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::in(['Électronique', 'Vêtements', 'Maison', 'Livres', 'Sports', 'Beauté'])
            ],
            'statut' => ['sometimes', 'required', Rule::in(['actif', 'inactif'])],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'supprimer_image' => ['sometimes', 'boolean'], // Pour gérer la suppression d'image
            'supprimer_pdf' => ['sometimes', 'boolean'],   // Pour gérer la suppression de PDF
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            // Messages pour nom_produit
            'nom_produit.required' => 'Le nom du produit est obligatoire.',
            'nom_produit.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'nom_produit.max' => 'Le nom du produit ne peut pas dépasser :max caractères.',
            'nom_produit.min' => 'Le nom du produit doit contenir au moins :min caractères.',

            // Messages pour code_produit
            'code_produit.required' => 'Le code produit est obligatoire.',
            'code_produit.unique' => 'Ce code produit est déjà utilisé par un autre produit.',
            'code_produit.regex' => 'Le code produit doit suivre le format : 2-3 lettres majuscules suivies de 3-5 chiffres (ex: ABC123).',

            // Messages pour prix
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre valide.',
            'prix.min' => 'Le prix minimum est de 0,01 €.',
            'prix.max' => 'Le prix ne peut pas dépasser :max €.',

            // Messages pour quantite
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.integer' => 'La quantité doit être un nombre entier.',
            'quantite.min' => 'La quantité ne peut pas être négative.',
            'quantite.max' => 'La quantité ne peut pas dépasser :max unités.',

            // Messages pour image
            'image.image' => 'Le fichier doit être une image valide.',
            'image.mimes' => 'L\'image doit être au format : :values.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',

            // Messages pour fichier_pdf
            'fichier_pdf.mimes' => 'Le document doit être au format PDF uniquement.',
            'fichier_pdf.max' => 'Le fichier PDF ne doit pas dépasser 5 Mo.',

            // Messages pour categorie
            'categorie.required' => 'La catégorie est obligatoire.',
            'categorie.in' => 'La catégorie sélectionnée n\'est pas valide.',

            // Messages pour statut
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut doit être "actif" ou "inactif".',

            // Messages pour description
            'description.max' => 'La description ne peut pas dépasser :max caractères.',

            // Messages pour les suppressions
            'supprimer_image.boolean' => 'Le champ de suppression d\'image doit être vrai ou faux.',
            'supprimer_pdf.boolean' => 'Le champ de suppression de PDF doit être vrai ou faux.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nom_produit' => 'nom du produit',
            'code_produit' => 'code produit',
            'prix' => 'prix',
            'quantite' => 'quantité',
            'image' => 'image du produit',
            'fichier_pdf' => 'document PDF',
            'categorie' => 'catégorie',
            'statut' => 'statut',
            'description' => 'description',
            'supprimer_image' => 'suppression d\'image',
            'supprimer_pdf' => 'suppression de PDF',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normaliser le code produit en majuscules si présent
        if ($this->has('code_produit')) {
            $this->merge([
                'code_produit' => strtoupper($this->code_produit),
            ]);
        }

        // Nettoyer le prix si présent
        if ($this->has('prix')) {
            $this->merge([
                'prix' => str_replace(' ', '', $this->prix),
            ]);
        }

        // Convertir les cases à cocher en booléens
        if ($this->has('supprimer_image')) {
            $this->merge([
                'supprimer_image' => filter_var($this->supprimer_image, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        if ($this->has('supprimer_pdf')) {
            $this->merge([
                'supprimer_pdf' => filter_var($this->supprimer_pdf, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $product = $this->route('product');

            // Règle métier : ne pas pouvoir passer un produit en inactif s'il a du stock
            if ($this->filled('statut') && $this->statut === 'inactif') {
                $currentQuantite = $this->filled('quantite') ? $this->quantite : $product->quantite;
                if ($currentQuantite > 0) {
                    $validator->errors()->add(
                        'statut',
                        'Impossible de désactiver un produit qui a encore du stock. Quantité actuelle : ' . $currentQuantite
                    );
                }
            }

            // Validation conditionnelle pour les produits coûteux
            $currentPrix = $this->filled('prix') ? $this->prix : $product->prix;
            if ($currentPrix > 1000) {
                // Si le produit est coûteux et qu'on supprime l'image sans en ajouter une nouvelle
                if ($this->filled('supprimer_image') && $this->supprimer_image && !$this->hasFile('image')) {
                    if (!$product->image) {
                        $validator->errors()->add(
                            'image',
                            'Une image est obligatoire pour les produits coûteux (>1000€).'
                        );
                    }
                }
            }

            // Empêcher la suppression d'image et de PDF en même temps si c'est la seule documentation
            if ($this->filled('supprimer_image') && $this->supprimer_image &&
                $this->filled('supprimer_pdf') && $this->supprimer_pdf &&
                !$this->hasFile('image') && !$this->hasFile('fichier_pdf')) {
                
                $validator->errors()->add(
                    'supprimer_image',
                    'Vous ne pouvez pas supprimer à la fois l\'image et le PDF sans en fournir de nouveaux.'
                );
            }
        });
    }

    /**
     * Get the validated data from the request.
     * Méthode personnalisée pour traiter les données après validation
     */
    public function validatedWithFiles(): array
    {
        $validated = $this->validated();

        // Traitement des fichiers supprimés
        if (isset($validated['supprimer_image']) && $validated['supprimer_image']) {
            $validated['image'] = null;
        }

        if (isset($validated['supprimer_pdf']) && $validated['supprimer_pdf']) {
            $validated['fichier_pdf'] = null;
        }

        // Nettoyer les champs de suppression pour éviter qu'ils soient sauvegardés
        unset($validated['supprimer_image'], $validated['supprimer_pdf']);

        return $validated;
    }
}