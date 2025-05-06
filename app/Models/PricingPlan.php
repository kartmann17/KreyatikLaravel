<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingPlan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'monthly_price',
        'annual_price',
        'features',
        'is_highlighted',
        'highlight_text',
        'button_text',
        'starting_text',
        'order',
        'is_active',
        'is_custom_plan',
        'custom_plan_text',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_highlighted' => 'boolean',
        'is_custom_plan' => 'boolean',
        'order' => 'integer',
        'features' => 'array',
    ];

    /**
     * Les accesseurs à ajouter au modèle.
     *
     * @var array
     */
    protected $appends = [
        'yearly_saving',
    ];

    /**
     * Scope pour obtenir uniquement les plans actifs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour ordonner les plans selon l'ordre défini.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Retourne le plan mis en évidence.
     */
    public function scopeHighlighted($query)
    {
        return $query->where('is_highlighted', true);
    }

    /**
     * Vérifie si le plan est mis en évidence.
     */
    public function isHighlighted()
    {
        return $this->is_highlighted;
    }

    /**
     * Vérifie si c'est un plan sur mesure.
     */
    public function isCustomPlan()
    {
        return $this->is_custom_plan;
    }

    /**
     * Définit les features comme tableau.
     */
    public function setFeaturesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['features'] = json_encode($value);
        } else if (is_string($value)) {
            $features = explode("\n", $value);
            $features = array_map('trim', $features);
            $features = array_filter($features);
            $this->attributes['features'] = json_encode($features);
        }
    }

    /**
     * Calculer l'économie annuelle (par rapport au paiement mensuel)
     * 
     * @return float
     */
    public function getYearlySavingAttribute()
    {
        return ($this->monthly_price * 12) - $this->annual_price;
    }

    /**
     * Calculer le pourcentage d'économie
     * 
     * @return int
     */
    public function getYearlySavingPercentageAttribute()
    {
        $monthly = $this->monthly_price * 12;
        if ($monthly == 0) return 0;
        
        return round(($this->yearly_saving / $monthly) * 100);
    }

    /**
     * Prétraiter l'attribut features avant la sérialisation.
     *
     * @param  string|null  $value
     * @return array|null
     */
    public function getFeaturesAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Récupérer le prix formaté pour l'affichage mensuel.
     */
    public function getFormattedMonthlyPriceAttribute()
    {
        return number_format($this->monthly_price, 2) . ' €';
    }

    /**
     * Récupérer le prix formaté pour l'affichage annuel.
     */
    public function getFormattedAnnualPriceAttribute()
    {
        return number_format($this->annual_price, 2) . ' €';
    }

    /**
     * Calculer l'économie réalisée avec l'abonnement annuel.
     */
    public function getYearlySavingsAttribute()
    {
        $monthlyTotal = $this->monthly_price * 12;
        $savings = $monthlyTotal - $this->annual_price;
        
        return [
            'amount' => $savings,
            'percentage' => $monthlyTotal > 0 ? round(($savings / $monthlyTotal) * 100) : 0,
            'formatted' => number_format($savings, 2) . ' €'
        ];
    }

    /**
     * Calculer l'économie réalisée avec un abonnement annuel.
     *
     * @return float
     */
    public function getAnnualSavingsAttribute()
    {
        $monthlyCost = $this->monthly_price * 12;
        $yearlyCost = $this->annual_price;
        
        if ($monthlyCost <= $yearlyCost) {
            return 0;
        }
        
        return $monthlyCost - $yearlyCost;
    }

    /**
     * Calculer le pourcentage d'économie avec un abonnement annuel.
     *
     * @return int
     */
    public function getAnnualSavingsPercentageAttribute()
    {
        $monthlyCost = $this->monthly_price * 12;
        
        if ($monthlyCost <= 0) {
            return 0;
        }
        
        $savings = $this->annual_savings;
        
        return round(($savings / $monthlyCost) * 100);
    }

    /**
     * Récupérer tous les plans actifs, triés par ordre.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActivePlans()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}
