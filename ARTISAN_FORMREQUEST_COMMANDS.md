# 🛠️ Commandes Artisan pour FormRequest

Ce document montre comment générer rapidement des FormRequest avec Laravel Artisan.

## 📋 Commandes de base

### Créer un FormRequest simple
```bash
php artisan make:request UserRequest
```
Crée : `app/Http/Requests/UserRequest.php`

### Créer plusieurs FormRequest en une fois
```bash
# Pour un modèle "Product"
php artisan make:request StoreProductRequest
php artisan make:request UpdateProductRequest
php artisan make:request DeleteProductRequest
```

### Générer avec un namespace personnalisé
```bash
php artisan make:request Admin/CreateUserRequest
```
Crée : `app/Http/Requests/Admin/CreateUserRequest.php`

## 🎯 Template de base généré par Artisan

Quand vous exécutez `php artisan make:request MonRequest`, Laravel génère :

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false; // ⚠️ ATTENTION : false par défaut !
    }

    public function rules(): array
    {
        return [
            // Ajoutez vos règles ici
        ];
    }
}
```

## ⚠️ Points d'attention pour vos étudiants

### 1. **authorize() retourne false par défaut**
```php
// ❌ FAUX - bloquera toutes les requêtes
public function authorize(): bool
{
    return false; // Erreur 403 automatique !
}

// ✅ CORRECT - selon votre logic métier
public function authorize(): bool
{
    return true; // Ou votre logique d'autorisation
}
```

### 2. **Structure recommandée pour un projet**
```
app/Http/Requests/
├── User/
│   ├── StoreUserRequest.php
│   ├── UpdateUserRequest.php
│   └── DeleteUserRequest.php
├── Product/
│   ├── StoreProductRequest.php
│   └── UpdateProductRequest.php
└── Auth/
    ├── LoginRequest.php
    └── RegisterRequest.php
```

## 🚀 Script de génération automatique

Créez ce script pour générer rapidement les FormRequest d'un modèle :

```bash
#!/bin/bash
# generate-requests.sh

if [ -z "$1" ]; then
    echo "Usage: ./generate-requests.sh ModelName"
    echo "Exemple: ./generate-requests.sh Product"
    exit 1
fi

MODEL=$1
LOWER_MODEL=$(echo $MODEL | tr '[:upper:]' '[:lower:]')

echo "🚀 Génération des FormRequest pour le modèle $MODEL..."

# Créer le dossier
mkdir -p "app/Http/Requests/$MODEL"

# Générer les FormRequest
php artisan make:request "$MODEL/Store${MODEL}Request"
php artisan make:request "$MODEL/Update${MODEL}Request"
php artisan make:request "$MODEL/Delete${MODEL}Request"

echo "✅ FormRequest générés avec succès !"
echo "📁 Vérifiez : app/Http/Requests/$MODEL/"

# Optionnel : générer aussi le contrôleur
read -p "❓ Générer aussi le contrôleur Resource ? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan make:controller "${MODEL}Controller" --resource
    echo "✅ Contrôleur ${MODEL}Controller généré !"
fi
```

## 📝 Bonnes pratiques de nommage

### Convention recommandée :
- `StoreXXXRequest` ➜ Pour CREATE (POST)
- `UpdateXXXRequest` ➜ Pour UPDATE (PUT/PATCH)
- `DeleteXXXRequest` ➜ Pour DELETE (si autorisation complexe)

### Exemples concrets :
```bash
php artisan make:request StoreUserRequest      # Création utilisateur
php artisan make:request UpdateUserRequest     # Mise à jour utilisateur
php artisan make:request StoreBlogPostRequest  # Création d'article
php artisan make:request UpdateBlogPostRequest # Mise à jour d'article
```

## 🎓 Exercice pratique pour vos étudiants

1. **Générer les FormRequest pour un modèle "Event"** :
```bash
php artisan make:request StoreEventRequest
php artisan make:request UpdateEventRequest
```

2. **Implémenter les règles** :
   - Nom requis (min: 3, max: 100)
   - Description optionnelle (max: 1000)
   - Date début requise (future)
   - Date fin requise (après date début)
   - Places disponibles (min: 1, max: 1000)
   - Prix optionnel (min: 0)

3. **Ajouter l'autorisation** :
   - CREATE : utilisateurs connectés uniquement
   - UPDATE : propriétaire de l'événement ou admin

4. **Messages personnalisés** en français

## 🔧 Tips pour le développement

### Voir toutes les commandes make :
```bash
php artisan list make
```

### Publier les stubs pour personnaliser les templates :
```bash
php artisan stub:publish
```
Permet de modifier les templates par défaut dans `stubs/`