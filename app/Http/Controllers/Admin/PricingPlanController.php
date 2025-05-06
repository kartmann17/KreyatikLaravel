<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PricingPlanController extends Controller
{
    /**
     * Afficher la liste des plans tarifaires
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pricingPlans = PricingPlan::orderBy('order')->paginate(10);
        return view('admin.pricing-plans.index', compact('pricingPlans'));
    }

    /**
     * Afficher le formulaire de création d'un plan
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.pricing-plans.create');
    }

    /**
     * Enregistrer un nouveau plan tarifaire
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $this->validatePlan($request);
        
        // Gérer les cases à cocher
        $validated['is_highlighted'] = $request->has('is_highlighted');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_custom_plan'] = $request->has('is_custom_plan');
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        PricingPlan::create($validated);
        
        return redirect()->route('admin.pricing-plans.index')
            ->with('success', 'Plan tarifaire créé avec succès !');
    }

    /**
     * Afficher le formulaire de modification d'un plan
     *
     * @param  \App\Models\PricingPlan  $pricingPlan
     * @return \Illuminate\View\View
     */
    public function edit(PricingPlan $pricingPlan)
    {
        return view('admin.pricing-plans.edit', compact('pricingPlan'));
    }

    /**
     * Mettre à jour un plan tarifaire
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PricingPlan  $pricingPlan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, PricingPlan $pricingPlan)
    {
        $validated = $this->validatePlan($request);
        
        // Gérer les cases à cocher
        $validated['is_highlighted'] = $request->has('is_highlighted');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_custom_plan'] = $request->has('is_custom_plan');
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        $pricingPlan->update($validated);
        
        return redirect()->route('admin.pricing-plans.index')
            ->with('success', 'Plan tarifaire mis à jour avec succès !');
    }

    /**
     * Supprimer un plan tarifaire
     *
     * @param  \App\Models\PricingPlan  $pricingPlan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(PricingPlan $pricingPlan)
    {
        try {
            $pricingPlan->delete();
            return redirect()->route('admin.pricing-plans.index')
                ->with('success', 'Plan tarifaire supprimé avec succès !');
        } catch (\Exception $e) {
            return redirect()->route('admin.pricing-plans.index')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un plan tarifaire avec méthode POST (alternative à DELETE)
     *
     * @param  \App\Models\PricingPlan  $pricingPlan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(PricingPlan $pricingPlan)
    {
        try {
            // Utilisez forceDelete si vous voulez contourner la SoftDelete
            $pricingPlan->forceDelete();
            return redirect()->route('admin.pricing-plans.index')
                ->with('success', 'Plan tarifaire supprimé avec succès !');
        } catch (\Exception $e) {
            return redirect()->route('admin.pricing-plans.index')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour l'ordre des plans
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'plans' => 'required|array',
            'plans.*.id' => 'required|exists:pricing_plans,id',
            'plans.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->plans as $plan) {
            PricingPlan::find($plan['id'])->update(['order' => $plan['order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Valider les données du plan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function validatePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'monthly_price' => 'required|string|max:50',
            'annual_price' => 'required|string|max:50',
            'features' => 'nullable|string',
            'is_highlighted' => 'nullable|boolean',
            'highlight_text' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'starting_text' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'is_custom_plan' => 'nullable|boolean',
            'custom_plan_text' => 'nullable|string|max:255',
            'order' => 'integer|min:0',
        ]);
        
        // Convertir les features de texte à tableau
        if (!empty($validated['features'])) {
            $features = explode("\n", $validated['features']);
            $features = array_map('trim', $features);
            $features = array_filter($features);
            $validated['features'] = $features;
        }
        
        return $validated;
    }
}
