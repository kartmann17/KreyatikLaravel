<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Expense extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'amount',
        'expense_date',
        'type',
        'category',
        'is_recurring',
        'user_id',
    ];
    
    /**
     * Les attributs à caster.
     *
     * @var array
     */
    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
    ];
    
    /**
     * Get the user that owns the expense.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope pour récupérer les dépenses mensuelles.
     */
    public function scopeMonthly($query)
    {
        return $query->where('type', 'monthly');
    }
    
    /**
     * Scope pour récupérer les dépenses annuelles.
     */
    public function scopeAnnual($query)
    {
        return $query->where('type', 'annual');
    }
    
    /**
     * Scope pour récupérer les dépenses ponctuelles.
     */
    public function scopeOneTime($query)
    {
        return $query->where('type', 'one_time');
    }
    
    /**
     * Scope pour récupérer les dépenses d'un mois spécifique.
     */
    public function scopeForMonth($query, $month, $year = null)
    {
        $year = $year ?: date('Y');
        
        return $query->whereMonth('expense_date', $month)
                    ->whereYear('expense_date', $year);
    }
    
    /**
     * Scope pour récupérer les dépenses d'une année spécifique.
     */
    public function scopeForYear($query, $year = null)
    {
        $year = $year ?: date('Y');
        
        return $query->whereYear('expense_date', $year);
    }
    
    /**
     * Retourne le format de date formaté.
     */
    public function getFormattedDateAttribute()
    {
        return $this->expense_date->format('d/m/Y');
    }
    
    /**
     * Retourne le montant formaté.
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, ',', ' ') . ' €';
    }
}
