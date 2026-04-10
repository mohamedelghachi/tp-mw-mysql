# Table Products - Documentation

## 🗂️ Structure de la table

La table `products` a été créée avec les champs suivants :

| Champ | Type | Description | Contraintes |
|-------|------|-------------|-------------|
| `id` | bigint | Clé primaire auto-incrémentée | NOT NULL, PRIMARY KEY |
| `nom_produit` | string(255) | Nom du produit | NOT NULL |
| `code_produit` | string(255) | Code unique du produit | NOT NULL, UNIQUE |
| `prix` | decimal(10,2) | Prix du produit en euros | NOT NULL |
| `quantite` | integer | Quantité en stock | NOT NULL, DEFAULT 0 |
| `image` | string(255) | Chemin vers l'image du produit | NULLABLE |
| `fichier_pdf` | string(255) | Chemin vers le fichier PDF | NULLABLE |
| `categorie` | string(255) | Catégorie du produit | NOT NULL |
| `statut` | enum | Statut du produit (actif/inactif) | NOT NULL, DEFAULT 'actif' |
| `created_at` | timestamp | Date de création | NULLABLE |
| `updated_at` | timestamp | Date de mise à jour | NULLABLE |

## 📦 Fichiers créés

### Migration
- `database/migrations/2026_04_10_120000_create_products_table.php`

### Modèle
- `app/Models/Product.php`
  - Méthodes utiles : `enStock()`, `aUneImage()`, `aUnFichierPdf()`
  - Scopes : `actif()`, `inactif()`
  - Accessor : `getPrixFormateAttribute()`

### Contrôleur
- `app/Http/Controllers/ProductController.php`
  - CRUD complet avec gestion des uploads
  - Validation des données
  - Messages d'erreur en français

### Seeder et Factory
- `database/seeders/ProductSeeder.php` - Données d'exemple
- `database/factories/ProductFactory.php` - Factory pour les tests

### Routes
- Routes RESTful ajoutées dans `routes/web.php`
- Protégées par middleware `auth`

## 🚀 Instructions d'utilisation

### 1. Exécuter la migration
```bash
php artisan migrate
```

### 2. Peupler la base avec des données d'exemple
```bash
php artisan db:seed --class=ProductSeeder
```

### 3. Créer le lien symbolique pour le stockage (si pas déjà fait)
```bash
php artisan storage:link
```

### 4. Routes disponibles
- `GET /products` - Liste des produits
- `GET /products/create` - Formulaire de création
- `POST /products` - Créer un produit
- `GET /products/{id}` - Voir un produit
- `GET /products/{id}/edit` - Formulaire d'édition
- `PUT /products/{id}` - Mettre à jour un produit
- `DELETE /products/{id}` - Supprimer un produit

## 💾 Stockage des fichiers

Les fichiers sont stockés dans :
- Images : `storage/app/public/products/images/`
- PDFs : `storage/app/public/products/pdfs/`

Accessible via les URLs :
- Images : `/storage/products/images/nom_fichier.jpg`
- PDFs : `/storage/products/pdfs/nom_fichier.pdf`

## 🧪 Utilisation du modèle

```php
use App\Models\Product;

// Créer un produit
$product = Product::create([
    'nom_produit' => 'Ordinateur Portable',
    'code_produit' => 'PC-001',
    'prix' => 899.99,
    'quantite' => 10,
    'categorie' => 'Informatique',
    'statut' => 'actif'
]);

// Récupérer les produits actifs
$produitsActifs = Product::actif()->get();

// Vérifier le stock
if ($product->enStock()) {
    echo "Produit en stock";
}

// Prix formaté
echo $product->prix_formate; // "899,99 €"
```

## 🎨 Prochaines étapes suggérées

1. Créer les vues Blade pour l'interface utilisateur
2. Ajouter une table `categories` séparée
3. Implémenter la recherche et filtres
4. Ajouter des tests unitaires
5. Créer une API REST pour les produits