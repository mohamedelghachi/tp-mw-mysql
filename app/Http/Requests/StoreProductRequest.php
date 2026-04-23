<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pour l'exemple pédagogique : vous pouvez personnaliser l'autorisation ici
        // Par exemple, vérifier si l'utilisateur a le rôle 'admin'
        // return auth()->user()->hasRole('admin');
        
        // Pour le moment, on autorise tous les utilisateurs connectés
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nom_produit' => ['required', 'string', 'max:255', 'min:3'],
            'code_produit' => ['required', 'string', 'max:100', 'unique:products,code_produit', 'regex:/^[A-Z]{2,3}[0-9]{3,5}$/'],
            'prix' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'quantite' => ['required', 'integer', 'min:0', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'fichier_pdf' => ['nullable', 'mimes:pdf', 'max:5120'],
            'categorie' => ['required', 'string', 'max:100', Rule::in(['Électronique', 'Vêtements', 'Maison', 'Livres', 'Sports', 'Beauté'])],
            'statut' => ['required', Rule::in(['actif', 'inactif'])],
            'description' => ['nullable', 'string', 'max:1000'],
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
            'code_produit.unique' => 'Ce code produit existe déjà dans la base de données.',
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
            'categorie.in' => 'La catégorie sélectionnée n\'est pas valide. Choisissez parmi : :values.',

            // Messages pour statut
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut doit être "actif" ou "inactif".',

            // Messages pour description
            'description.max' => 'La description ne peut pas dépasser :max caractères.',
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
        ];
    }

    /**
     * Prepare the data for validation.
     * Cette méthode permet de modifier les données avant la validation
     */
    protected function prepareForValidation(): void
    {
        // Exemple : normaliser le code produit en majuscules
        if ($this->has('code_produit')) {
            $this->merge([
                'code_produit' => strtoupper($this->code_produit),
            ]);
        }

        // Exemple : nettoyer le prix (enlever les espaces)
        if ($this->has('prix')) {
            $this->merge([
                'prix' => str_replace(' ', '', $this->prix),
            ]);
        }
    }

    /**
     * Configure the validator instance.
     * Cette méthode permet d'ajouter des validations personnalisées
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Exemple : règle métier personnalisée
            if ($this->quantite > 0 && $this->statut === 'inactif') {
                $validator->errors()->add(
                    'statut', 
                    'Un produit avec une quantité en stock ne peut pas être inactif.'
                );
            }

            // Exemple : validation conditionnelle
            if ($this->prix > 1000 && !$this->hasFile('image')) {
                $validator->errors()->add(
                    'image', 
                    'Une image est obligatoire pour les produits coûteux (>1000€).'
                );
            }
        });
    }
}