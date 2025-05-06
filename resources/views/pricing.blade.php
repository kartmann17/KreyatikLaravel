@extends('layouts.app')

@section('title', 'Nos Tarifs')

@section('content')
<div class="pricing-header bg-light py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Nos Tarifs</h1>
                <p class="lead text-muted mb-4">Choisissez le plan qui correspond le mieux à vos besoins</p>
                
                <div class="d-flex justify-content-center mb-5">
                    <div class="form-check form-switch form-check-inline me-2">
                        <input class="form-check-input" type="checkbox" id="pricingPeriodSwitch">
                        <label class="form-check-label" for="pricingPeriodSwitch">
                            <span class="pricing-monthly fw-bold">Mensuel</span>
                            <span class="mx-2">/</span>
                            <span class="pricing-annual text-muted">Annuel <span class="badge bg-success">-15%</span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center g-4">
        @forelse($pricingPlans as $plan)
            <div class="col-md-6 col-lg-4">
                <div class="card pricing-card h-100 {{ $plan->is_highlighted ? 'pricing-card-highlight border-warning shadow' : '' }}">
                    @if($plan->is_highlighted && $plan->highlight_text)
                        <div class="card-highlight-badge">{{ $plan->highlight_text }}</div>
                    @endif
                    
                    <div class="card-header bg-transparent text-center py-4 border-bottom-0">
                        <h3 class="card-title mb-1">{{ $plan->name }}</h3>
                        <p class="text-muted mb-0">{{ $plan->introduction }}</p>
                    </div>
                    
                    <div class="card-body text-center pt-0">
                        <div class="pricing-price-container mb-4">
                            <div class="pricing-monthly-container">
                                <h2 class="pricing-price mb-0">{{ $plan->monthly_price }} €</h2>
                                <span class="text-muted">par mois</span>
                            </div>
                            <div class="pricing-annual-container d-none">
                                <h2 class="pricing-price mb-0">{{ $plan->annual_price }} €</h2>
                                <span class="text-muted">par an</span>
                                <div class="annual-savings small text-success mt-1">
                                    Économisez {{ ($plan->monthly_price * 12) - $plan->annual_price }} € par an
                                </div>
                            </div>
                        </div>
                        
                        <div class="pricing-features mb-4">
                            <ul class="list-unstyled">
                                @foreach(explode("\n", $plan->features) as $feature)
                                    @if(trim($feature))
                                        <li class="py-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            {{ trim($feature) }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent text-center border-top-0 pb-4">
                        @if($plan->is_custom_plan)
                            <a href="{{ route('contact', ['subject' => 'Demande de devis pour ' . $plan->name]) }}" class="btn {{ $plan->is_highlighted ? 'btn-warning' : 'btn-outline-primary' }} btn-lg w-100">
                                {{ $plan->button_text ?: 'Demander un devis' }}
                            </a>
                        @else
                            <a href="{{ route('register', ['plan' => $plan->slug]) }}" class="btn {{ $plan->is_highlighted ? 'btn-warning' : 'btn-outline-primary' }} btn-lg w-100">
                                {{ $plan->button_text ?: 'Choisir ce plan' }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="alert alert-info">
                    Aucun plan tarifaire n'est disponible pour le moment.<br>
                    Veuillez nous contacter pour plus d'informations.
                </div>
                <a href="{{ route('contact') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-envelope me-2"></i> Nous contacter
                </a>
            </div>
        @endforelse
    </div>
    
    @if($customPlanText)
        <div class="row mt-5 pt-4">
            <div class="col-12 text-center">
                <div class="custom-plan-box p-4 rounded bg-light">
                    <h3 class="h4 mb-3">{{ $customPlanTitle ?? 'Besoin d\'une solution personnalisée ?' }}</h3>
                    <p class="mb-4">{{ $customPlanText }}</p>
                    <a href="{{ route('contact', ['subject' => 'Demande de solution personnalisée']) }}" class="btn btn-primary">
                        {{ $customPlanButtonText ?? 'Contactez-nous' }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection

@section('css')
<style>
    .pricing-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .pricing-card:hover {
        transform: translateY(-5px);
    }
    
    .pricing-card-highlight {
        position: relative;
        z-index: 2;
        transform: scale(1.05);
    }
    
    .pricing-card-highlight:hover {
        transform: translateY(-5px) scale(1.05);
    }
    
    .card-highlight-badge {
        position: absolute;
        top: 0;
        right: 0;
        background-color: #ffc107;
        color: #212529;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-bottom-left-radius: 8px;
        font-size: 0.85rem;
    }
    
    .pricing-features li {
        border-bottom: 1px dashed rgba(0,0,0,0.1);
    }
    
    .pricing-features li:last-child {
        border-bottom: none;
    }
    
    .custom-plan-box {
        border: 1px dashed rgba(0,0,0,0.2);
    }
    
    .form-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
        cursor: pointer;
    }
    
    .form-check-label {
        cursor: pointer;
    }
</style>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pricingSwitch = document.getElementById('pricingPeriodSwitch');
        const monthlyText = document.querySelector('.pricing-monthly');
        const annualText = document.querySelector('.pricing-annual');
        const monthlyContainers = document.querySelectorAll('.pricing-monthly-container');
        const annualContainers = document.querySelectorAll('.pricing-annual-container');
        
        pricingSwitch.addEventListener('change', function() {
            if (this.checked) {
                // Annual
                monthlyText.classList.add('text-muted');
                monthlyText.classList.remove('fw-bold');
                annualText.classList.remove('text-muted');
                annualText.classList.add('fw-bold');
                
                monthlyContainers.forEach(container => container.classList.add('d-none'));
                annualContainers.forEach(container => container.classList.remove('d-none'));
            } else {
                // Monthly
                monthlyText.classList.remove('text-muted');
                monthlyText.classList.add('fw-bold');
                annualText.classList.add('text-muted');
                annualText.classList.remove('fw-bold');
                
                monthlyContainers.forEach(container => container.classList.remove('d-none'));
                annualContainers.forEach(container => container.classList.add('d-none'));
            }
        });
    });
</script>
@endsection 