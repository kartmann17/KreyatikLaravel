<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Affiche la liste des dépenses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $query = Expense::where('user_id', Auth::id())
                        ->orderBy('expense_date', 'desc');
        
        // Filtrer par type
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        // Filtrer par mois/année
        if ($request->has('month')) {
            $query->forMonth($month, $year);
        } elseif ($request->has('year')) {
            $query->forYear($year);
        }
        
        $expenses = $query->paginate(15);
        
        // Calcul des statistiques
        $totalAmount = $query->sum('amount');
        $monthlyTotal = Expense::where('user_id', Auth::id())
                            ->forMonth(date('m'), date('Y'))
                            ->sum('amount');
        $yearlyTotal = Expense::where('user_id', Auth::id())
                            ->forYear(date('Y'))
                            ->sum('amount');
        
        // Grouper par catégorie pour les statistiques
        $categoriesData = Expense::where('user_id', Auth::id())
                               ->selectRaw('category, SUM(amount) as total')
                               ->groupBy('category')
                               ->orderBy('total', 'desc')
                               ->get();
        
        // Récupération de toutes les catégories distinctes pour le filtre
        $categories = Expense::where('user_id', Auth::id())
                        ->whereNotNull('category')
                        ->distinct()
                        ->pluck('category')
                        ->toArray();
        
        return view('admin.expenses.index', compact(
            'expenses', 
            'type', 
            'month', 
            'year', 
            'totalAmount', 
            'monthlyTotal', 
            'yearlyTotal', 
            'categoriesData',
            'categories'
        ));
    }

    /**
     * Affiche le formulaire de création d'une dépense.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.expenses.create');
    }

    /**
     * Enregistre une nouvelle dépense.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'type' => 'required|in:monthly,annual,one_time',
            'category' => 'nullable|string|max:255',
            'is_recurring' => 'boolean',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        Expense::create($validated);
        
        return redirect()->route('admin.expenses.index')
                         ->with('success', 'Dépense ajoutée avec succès.');
    }

    /**
     * Affiche les détails d'une dépense.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($id);
        
        return view('admin.expenses.show', compact('expense'));
    }

    /**
     * Affiche le formulaire d'édition d'une dépense.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($id);
        
        return view('admin.expenses.edit', compact('expense'));
    }

    /**
     * Met à jour une dépense.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'type' => 'required|in:monthly,annual,one_time',
            'category' => 'nullable|string|max:255',
            'is_recurring' => 'boolean',
        ]);
        
        $expense->update($validated);
        
        return redirect()->route('admin.expenses.index')
                         ->with('success', 'Dépense mise à jour avec succès.');
    }

    /**
     * Supprime une dépense.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($id);
        $expense->delete();
        
        return redirect()->route('admin.expenses.index')
                         ->with('success', 'Dépense supprimée avec succès.');
    }
    
    /**
     * Affiche les statistiques des dépenses.
     *
     * @return \Illuminate\View\View
     */
    public function statistics()
    {
        // Récupère les 12 derniers mois
        $months = [];
        $monthlyData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $total = Expense::where('user_id', Auth::id())
                            ->forMonth($date->month, $date->year)
                            ->sum('amount');
            
            $monthlyData[] = $total;
        }
        
        // Dépenses par catégorie
        $categoryData = Expense::where('user_id', Auth::id())
                               ->selectRaw('category, SUM(amount) as total')
                               ->whereNotNull('category')
                               ->groupBy('category')
                               ->orderBy('total', 'desc')
                               ->get();
        
        // Dépenses par type
        $typeData = [
            'monthly' => Expense::where('user_id', Auth::id())->monthly()->sum('amount'),
            'annual' => Expense::where('user_id', Auth::id())->annual()->sum('amount'),
            'one_time' => Expense::where('user_id', Auth::id())->oneTime()->sum('amount'),
        ];
        
        return view('admin.expenses.statistics', compact(
            'months', 
            'monthlyData', 
            'categoryData', 
            'typeData'
        ));
    }
}
