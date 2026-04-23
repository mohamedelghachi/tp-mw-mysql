# FormRequest Laravel - Guide Pédagogique

## 🎯 Objectif de cette démonstration

Ce projet illustre l'utilisation des **FormRequest** dans Laravel pour améliorer l'organisation du code et la gestion de la validation.

## 📁 Structure des fichiers

```
app/Http/
├── Controllers/
│   ├── ProductController.php           # ❌ Version AVANT (validation directe)
│   └── ProductControllerRefactored.php # ✅ Version APRÈS (avec FormRequest)
└── Requests/
    ├── StoreProductRequest.php         # FormRequest pour création
    └── UpdateProductRequest.php        # FormRequest pour mise à jour
```

## 🔍 Comparaison : AVANT vs APRÈS

### ❌ AVANT - Validation directe dans le contrôleur

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'nom_produit' => 'required|string|max:255',
        'code_produit' => 'required|string|max:100|unique:products,code_produit',
        'prix' => 'required|numeric|min:0',
        // ... 20+ lignes de règles
    ], [
        'nom_produit.required' => 'Le nom du produit est obligatoire.',
        'code_produit.unique' => 'Ce code produit existe déjà.',
        // ... 15+ lignes de messages
    ]);

    // Logic métier mélangée avec la validation
    if ($request->quantite > 0 && $request->statut === 'inactif') {
        throw ValidationException::withMessages([
            'statut' => 'Un produit avec stock ne peut être inactif.',
        ]);
    }

    // Code de création...
}
```

### ✅ APRÈS - Avec FormRequest

```php
public function store(StoreProductRequest $request)
{
    // La validation est déjà faite automatiquement ! 🎉
    $validated = $request->validated();
    
    // Code de création propre et lisible
    $product = Product::create($validated);
    
    return redirect()->route('products.index')
        ->with('success', "Produit '{$product->nom_produit}' créé.");
}
```

## 🌟 Avantages des FormRequest

### 1. **Séparation des responsabilités** 
- ✅ Validation ➜ `StoreProductRequest`
- ✅ Logic métier ➜ Contrôleur
- ✅ Code plus propre et maintenable

### 2. **Réutilisabilité**
```php
// Peut être réutilisé dans d'autres contrôleurs
public function storeFromAPI(StoreProductRequest $request)
{
    // Même validation automatiquement ! 
}
```

### 3. **Validation automatique**
- ✅ Si validation échoue ➜ Redirect automatique avec erreurs
- ✅ Si validation réussit ➜ Méthode du contrôleur exécutée
- ✅ Plus de code `if ($validator->fails())` partout !

### 4. **Autorisation intégrée**
```php
public function authorize(): bool
{
    return auth()->user()->hasRole('admin');
}
```

### 5. **Messages d'erreur centralisés**
```php
public function messages(): array
{
    return [
        'prix.min' => 'Le prix minimum est de 0,01 €.',
        'code_produit.regex' => 'Format: ABC123 (2-3 lettres + 3-5 chiffres)',
    ];
}
```

## 🛠️ Fonctionnalités avancées démontrées

### 1. **Préparation des données** (`prepareForValidation()`)
```php
protected function prepareForValidation(): void
{
    $this->merge([
        'code_produit' => strtoupper($this->code_produit), // Normalisation auto
    ]);
}
```

### 2. **Validation conditionnelle** (`withValidator()`)
```php
public function withValidator($validator): void
{
    $validator->after(function ($validator) {
        if ($this->quantite > 0 && $this->statut === 'inactif') {
            $validator->errors()->add('statut', 'Message personnalisé');
        }
    });
}
```

### 3. **Attributs personnalisés** (`attributes()`)
```php
public function attributes(): array
{
    return [
        'nom_produit' => 'nom du produit',
        'prix' => 'prix',
    ];
}
```

### 4. **Règles avec paramètres dynamiques**
```php
public function rules(): array
{
    $productId = $this->route('product')->id; // Récupération depuis la route
    
    return [
        'code_produit' => [
            'required',
            Rule::unique('products')->ignore($productId), // ✅ Ignore l'ID actuel
            'regex:/^[A-Z]{2,3}[0-9]{3,5}$/' // ✅ Format spécifique
        ],
    ];
}
```

## 🧪 Exemples de validation métier

### Règles business complexes dans `StoreProductRequest` :
- ✅ Produit avec stock ne peut être inactif
- ✅ Produits >1000€ nécessitent une image
- ✅ Format de code produit stricte (ABC123)
- ✅ Catégories limitées à une liste prédéfinie

### Règles spécifiques à l'update dans `UpdateProductRequest` :
- ✅ Validation `sometimes` (champs optionnels en update)
- ✅ Gestion des suppressions d'images/PDF
- ✅ Empêche suppression simultanée image + PDF
- ✅ Règle unique qui ignore l'enregistrement actuel

## 📝 Utilisation dans vos vues

```html
<!-- Les erreurs s'affichent automatiquement -->
@if ($errors->has('nom_produit'))
    <div class="error">{{ $errors->first('nom_produit') }}</div>
@endif

<!-- Affichage de tous les messages personnalisés -->
@if ($errors->any())
    <ul class="errors">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
```

## 🎓 Pour vos étudiants

### Quand utiliser des FormRequest ?
1. ✅ **Validation complexe** (>3 règles par champ)
2. ✅ **Messages personnalisés** nécessaires
3. ✅ **Règles métier** à implémenter
4. ✅ **Réutilisabilité** dans plusieurs contrôleurs
5. ✅ **Autorisation** complexe

### Quand rester simple ?
- ❌ Validation très basique (1-2 règles)
- ❌ Formulaires jetables/prototypes
- ❌ Pas de logique métier

## 🚀 Exercices pratiques

1. **Créer un FormRequest pour les utilisateurs** avec :
   - Email unique
   - Mot de passe fort
   - Âge minimum 18 ans

2. **Ajouter une autorisation sophistiquée** :
   - Seuls les admins peuvent créer des produits >10000€
   - Les utilisateurs normaux sont limités à leur propre contenu

3. **Implémenter une règle métier** :
   - Un produit en promotion ne peut pas être inactif
   - Stock minimum selon la catégorie

## 🔗 Documentation Laravel
- [FormRequest officielle](https://laravel.com/docs/validation#form-request-validation)
- [Validation Rules](https://laravel.com/docs/validation#available-validation-rules)