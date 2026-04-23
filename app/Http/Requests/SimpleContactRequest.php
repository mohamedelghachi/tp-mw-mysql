<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest SIMPLE pour commencer - exemple pédagogique
 * 
 * Cette classe illustre le minimum requis pour un FormRequest
 * Parfait pour débuter et comprendre les concepts de base
 */
class SimpleContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * Cette méthode détermine si l'utilisateur a le droit d'exécuter cette requête
     * Si elle retourne false ➜ erreur 403 Forbidden automatiquement
     */
    public function authorize(): bool
    {
        // Pour un formulaire de contact, tout le monde peut l'utiliser
        return true;
        
        // Exemples plus avancés :
        // return auth()->check(); // Seuls les utilisateurs connectés
        // return auth()->user()->hasRole('admin'); // Seuls les admins
        // return $this->user()->owns($this->route('resource')); // Propriétaire uniquement
    }

    /**
     * Get the validation rules that apply to the request.
     * 
     * Cette méthode définit TOUTES les règles de validation
     * C'est ici que vous mettez vos contraintes de validation
     */
    public function rules(): array
    {
        return [
            // Champ requis, texte, maximum 100 caractères
            'nom' => 'required|string|max:100',
            
            // Email requis et valide
            'email' => 'required|email',
            
            // Sujet requis, minimum 5 caractères
            'sujet' => 'required|string|min:5|max:200',
            
            // Message requis, minimum 10 caractères
            'message' => 'required|string|min:10|max:1000',
        ];
    }

    /**
     * Définir des messages d'erreur personnalisés (optionnel)
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Votre nom est obligatoire.',
            'nom.max' => 'Votre nom ne peut pas dépasser :max caractères.',
            
            'email.required' => 'Votre adresse email est obligatoire.',
            'email.email' => 'Veuillez fournir une adresse email valide.',
            
            'sujet.required' => 'Le sujet de votre message est obligatoire.',
            'sujet.min' => 'Le sujet doit contenir au moins :min caractères.',
            
            'message.required' => 'Le message ne peut pas être vide.',
            'message.min' => 'Votre message doit contenir au moins :min caractères.',
            'message.max' => 'Votre message ne peut pas dépasser :max caractères.',
        ];
    }

    /**
     * Personnaliser les noms d'attributs (optionnel)
     * Utilisé pour remplacer le nom des champs dans les messages d'erreur alors
     */
    public function attributes(): array
    {
        return [
            'nom' => 'nom complet',
            'email' => 'adresse email',
            'sujet' => 'sujet du message',
            'message' => 'contenu du message',
        ];
    }
}

/*
 * UTILISATION dans un contrôleur :
 * 
 * class ContactController extends Controller
 * {
 *     public function store(SimpleContactRequest $request)
 *     {
 *         // À ce point, la validation est déjà passée ! 🎉
 *         $validated = $request->validated();
 *         
 *         // Traitement du formulaire de contact
 *         Mail::to('admin@example.com')->send(new ContactMail($validated));
 *         
 *         return back()->with('success', 'Message envoyé avec succès !');
 *     }
 * }
 * 
 * AVANTAGES vs validation directe :
 * ✅ Code plus propre
 * ✅ Validation réutilisable  
 * ✅ Messages centralisés
 * ✅ Autorisation automatique
 * ✅ Pas de if ($validator->fails()) partout !
 */