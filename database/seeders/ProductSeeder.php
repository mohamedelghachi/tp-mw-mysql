<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'nom_produit' => 'Ordinateur Portable HP',
            'code_produit' => 'HP-001',
            'prix' => 899.99,
            'quantite' => 15,
            'image' => 'images/hp-laptop.jpg',
            'fichier_pdf' => 'documents/hp-specs.pdf',
            'categorie' => 'Informatique',
            'statut' => 'actif',
        ]);

        Product::create([
            'nom_produit' => 'Smartphone Samsung Galaxy',
            'code_produit' => 'SAM-002',
            'prix' => 599.99,
            'quantite' => 25,
            'image' => 'images/samsung-phone.jpg',
            'categorie' => 'Téléphonie',
            'statut' => 'actif',
        ]);

        Product::create([
            'nom_produit' => 'Casque Audio Sony',
            'code_produit' => 'SONY-003',
            'prix' => 149.99,
            'quantite' => 0,
            'image' => 'images/sony-headphones.jpg',
            'fichier_pdf' => 'documents/sony-manual.pdf',
            'categorie' => 'Audio',
            'statut' => 'inactif',
        ]);

        Product::create([
            'nom_produit' => 'Tablette Apple iPad',
            'code_produit' => 'APPLE-004',
            'prix' => 449.99,
            'quantite' => 8,
            'image' => 'images/ipad.jpg',
            'categorie' => 'Informatique',
            'statut' => 'actif',
        ]);

        Product::create([
            'nom_produit' => 'Montre Connectée',
            'code_produit' => 'WATCH-005',
            'prix' => 199.99,
            'quantite' => 12,
            'categorie' => 'Accessoires',
            'statut' => 'actif',
        ]);
    }
}