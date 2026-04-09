# Guide Exercice 2 - Validation Avancée Laravel
## Système d'Inscription Utilisateur avec Mot de Passe Sécurisé

> **Objectif :** Comprendre et implémenter une validation complexe avec règles personnalisées

---

## 🎯 **Vue d'ensemble**
Vous allez créer un système d'inscription complet avec :
- Validation unique en base de données
- Règles de mot de passe complexes
- Messages personnalisés en français
- Validation conditionnelle

---

## 📋 **Étape 1 : Création du Contrôleur**

**Commande :**
```bash
php artisan make:controller RegistrationController
```

**Structure du contrôleur :**
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        // Retourne la vue d'inscription
        return view('register');
    }

    public function register(Request $request)
    {
        // 1. Validation des données
        // 2. Création de l'utilisateur
        // 3. Connexion automatique
        // 4. Redirection
    }
}
```

---

## 📋 **Étape 2 : Ajout des Routes**

**Dans `routes/web.php` :**
```php
use App\Http\Controllers\RegistrationController;

Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])
    ->name('register.form');
Route::post('/register', [RegistrationController::class, 'register'])
    ->name('register');
```

---

## 📋 **Étape 3 : Règles de Validation Avancées**

### **Règles de base :**
```php
$validated = $request->validate([
    'username' => 'required|min:3|max:20|unique:users,username|alpha_dash',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
    'birth_date' => 'required|date|before:today|after:1900-01-01',
    'phone' => 'nullable|regex:/^[0-9+\-\s()]+$/'
]);
```

### **Explication des règles :**

| Règle | Signification |
|-------|---------------|
| `alpha_dash` | Lettres, chiffres, tirets et underscores uniquement |
| `unique:users,username` | Vérifie l'unicité dans la table users, colonne username |
| `confirmed` | Nécessite un champ password_confirmation |
| `regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/` | Mot de passe avec maj, min, chiffre |
| `before:today` | Date antérieure à aujourd'hui |
| `nullable` | Le champ peut être vide |

---

## 📋 **Étape 4 : Messages Personnalisés**

### **Méthode 1 : Dans le contrôleur**
```php
$validated = $request->validate($rules, [
    'username.required' => 'Le nom d\'utilisateur est obligatoire.',
    'username.min' => 'Le nom d\'utilisateur doit contenir au moins :min caractères.',
    'username.max' => 'Le nom d\'utilisateur ne peut pas dépasser :max caractères.',
    'username.unique' => 'Ce nom d\'utilisateur est déjà pris.',
    'username.alpha_dash' => 'Le nom d\'utilisateur ne peut contenir que des lettres, chiffres, tirets et underscores.',
    
    'email.required' => 'L\'adresse email est obligatoire.',
    'email.email' => 'L\'adresse email doit être valide.',
    'email.unique' => 'Cette adresse email est déjà utilisée.',
    
    'password.required' => 'Le mot de passe est obligatoire.',
    'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
    'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
    'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.',
    
    'birth_date.required' => 'La date de naissance est obligatoire.',
    'birth_date.date' => 'La date de naissance doit être une date valide.',
    'birth_date.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
    'birth_date.after' => 'La date de naissance doit être postérieure à 1900.',
    
    'phone.regex' => 'Le format du téléphone n\'est pas valide.',
]);
```

---

## 📋 **Étape 5 : Validation Personnalisée Avancée**

### **Vérification de l'âge (18 ans minimum) :**
```php
use Carbon\Carbon;

// Dans la méthode register()
$birthDate = Carbon::parse($request->birth_date);
$age = $birthDate->diffInYears(Carbon::now());

if ($age < 18) {
    return back()->withErrors([
        'birth_date' => 'Vous devez être majeur (18 ans) pour vous inscrire.'
    ])->withInput();
}
```

### **Vérification des mots interdits :**
```php
$forbiddenUsernames = ['admin', 'root', 'administrator', 'moderator', 'test'];

if (in_array(strtolower($request->username), $forbiddenUsernames)) {
    return back()->withErrors([
        'username' => 'Ce nom d\'utilisateur n\'est pas autorisé.'
    ])->withInput();
}
```

---

## 📋 **Étape 6 : Création de l'Utilisateur**

```php
// Après validation réussie
$user = User::create([
    'username' => $validated['username'],
    'email' => $validated['email'],
    'password' => Hash::make($validated['password']),
    'birth_date' => $validated['birth_date'],
    'phone' => $validated['phone'] ?? null,
]);

// Connexion automatique
Auth::login($user);

// Redirection avec message de succès
return redirect()->route('dashboard')->with('success', 'Inscription réussie ! Bienvenue ' . $user->username . '!');
```

---

## 📋 **Étape 7 : Vue d'Inscription (register.blade.php)**

```html
@extends('layout')

@section('title', 'Inscription')

@section('content')
<h1>Inscription</h1>

{{-- Affichage des erreurs --}}
@if ($errors->any())
    <div style="color: red; margin-bottom: 20px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('register') }}" method="POST">
    @csrf
    
    <div>
        <label for="username">Nom d'utilisateur :</label><br>
        <input type="text" id="username" name="username" value="{{ old('username') }}" required>
        @error('username')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div><br>

    <div>
        <label for="email">Email :</label><br>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div><br>

    <div>
        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required>
        <small>Doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre</small>
        @error('password')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div><br>

    <div>
        <label for="password_confirmation">Confirmer le mot de passe :</label><br>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
    </div><br>

    <div>
        <label for="birth_date">Date de naissance :</label><br>
        <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
        @error('birth_date')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div><br>

    <div>
        <label for="phone">Téléphone (optionnel) :</label><br>
        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+33 1 23 45 67 89">
        @error('phone')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div><br>

    <button type="submit">S'inscrire</button>
</form>

<p><a href="{{ route('login.form') }}">Déjà inscrit ? Se connecter</a></p>
@endsection
```

---

## 📋 **Étape 8 : Validation JavaScript (Bonus)**

### **Dans la vue, avant la fermeture de </body> :**
```html
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('password_confirmation');
    
    // Validation en temps réel du mot de passe
    passwordField.addEventListener('input', function() {
        const password = this.value;
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
        
        if (password.length >= 8 && regex.test(password)) {
            this.style.borderColor = 'green';
        } else {
            this.style.borderColor = 'red';
        }
    });
    
    // Validation de la confirmation
    confirmField.addEventListener('input', function() {
        if (this.value === passwordField.value) {
            this.style.borderColor = 'green';
        } else {
            this.style.borderColor = 'red';
        }
    });
});
</script>
```

---

## 🚨 **Points d'Attention**

### **Erreurs Courantes :**
1. **Oublier le token CSRF** : `@csrf` dans le formulaire
2. **Username vs name** : Cohérence dans les noms de champs
3. **Hash du mot de passe** : `Hash::make()` obligatoire
4. **Confirmed** : Le champ doit s'appeler `password_confirmation`
5. **Old() dans les inputs** : Pour conserver les valeurs en cas d'erreur

### **Bonnes Pratiques :**
1. **Validation côté serveur TOUJOURS** : Même avec JavaScript
2. **Messages en français** : Meilleure UX
3. **Feedback visuel** : Couleurs pour les erreurs/succès
4. **Sécurité** : Ne jamais stocker les mots de passe en clair

---

## 🧪 **Tests à Effectuer**

1. **Champs vides** → Erreurs de validation
2. **Email invalide** → Erreur format
3. **Username déjà pris** → Erreur unique
4. **Mot de passe faible** → Erreur regex
5. **Confirmation différente** → Erreur confirmed
6. **Mineur** → Erreur âge
7. **Téléphone invalide** → Erreur format
8. **Inscription valide** → Succès + connexion

---

## 🎯 **Critères d'Évaluation**

| Critère | Points | Vérification |
|---------|--------|--------------|
| Contrôleur créé | 1 | Méthodes show+register |
| Routes définies | 1 | GET+POST /register |
| Règles de validation | 2 | Toutes les règles présentes |
| Messages personnalisés | 1 | En français |
| Validation mot de passe | 1 | Regex complexe |
| Validation âge | 1 | 18 ans minimum |
| Création utilisateur | 1 | Hash + Auth::login |

**Total : 8 points**

---

## 💡 **Astuces pour les Étudiants**

1. **Testez étape par étape** : Commencez simple, ajoutez la complexité
2. **Utilisez les outils Laravel** : `dd($request->all())` pour débugger
3. **Lisez les erreurs** : Laravel donne des messages précis
4. **Documentation** : https://laravel.com/docs/validation
5. **Communauté** : N'hésitez pas à demander de l'aide

---

**Temps estimé : 45 minutes**
**Niveau : Intermédiaire**