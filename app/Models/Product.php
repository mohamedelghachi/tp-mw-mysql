<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom_produit',
        'code_produit',
        'prix',
        'quantite',
        'image',
        'fichier_pdf',
        'categorie',
        'statut',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'prix' => 'decimal:2',
        'quantite' => 'integer',
        'statut' => 'string',
    ];

    /**
     * Relation plusieurs-à-plusieurs avec Order
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity')->withTimestamps();
    }

    /**
     * Scope pour les produits actifs
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour les produits inactifs
     */
    public function scopeInactif($query)
    {
        return $query->where('statut', 'inactif');
    }

    /**
     * Accessor pour le prix formaté
     */
    public function getPrixFormateAttribute()
    {
        return number_format($this->prix, 2, ',', ' ') . ' €';
    }

    /**
     * Vérifier si le produit est en stock
     */
    public function enStock()
    {
        return $this->quantite > 0;
    }

    /**
     * Vérifier si le produit a une image
     */
    public function aUneImage()
    {
        return !empty($this->image);
    }

    /**
     * Vérifier si le produit a un fichier PDF
     */
    public function aUnFichierPdf()
    {
        return !empty($this->fichier_pdf);
    }
    
}