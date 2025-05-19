<x-header title="Nos Offres" />
<main class="site-content">
  <section class="pricing-section mt-4">
    <div class="container">
        <h2 class="section-title">Choisissez votre plan</h2>
        <p class="section-subtitle">Des formules pensées pour accompagner votre croissance digitale.</p>

        <div class="pricing-cards mt-4 ">
            @forelse($pricingPlans as $plan)
            <div class="pricing-card {{ $plan->is_highlighted ? 'highlighted' : '' }}"
                 data-plan="{{ $plan->name }}"
                 data-price="{{ $plan->is_custom_plan ? $plan->monthly_price : $plan->monthly_price . '€/mois' }}"
                 data-full="{{ $plan->is_custom_plan ? $plan->annual_price : $plan->annual_price . '€' }}">

                @if($plan->is_highlighted && $plan->highlight_text)
                <div class="badge">{{ $plan->highlight_text }}</div>
                @endif

                <h3 class="plan-title">{{ $plan->name }}</h3>
                <p class="starting-text font-bold">{{ $plan->starting_text }}</p>
                <p class="price">{{ $plan->is_custom_plan ? $plan->monthly_price : $plan->monthly_price . '€/mois' }}</p>
                <p class="see-conditions"><a href="{{ route('conditions') }}" target="_blank">*Voir conditions</a></p>
                <p class="alt-price">
                    @if($plan->is_custom_plan)
                        {{ $plan->custom_plan_text }}
                    @else
                        ou {{ $plan->annual_price }}€ en une fois
                    @endif
                </p>
                <ul class="features">
                    @foreach($plan->features as $feature)
                    <li>{{ $feature }}</li>
                    @endforeach
                </ul>
                <div class="btn-wrapper">
                    <button class="btn-subscribe" onclick="openForm(this)">{{ $plan->button_text }}</button>
                </div>
            </div>
            @empty
            <div class="empty-plans">
                <p>Aucune offre disponible pour le moment. Veuillez nous contacter pour plus d'informations.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Formulaire modal -->
<div class="modal-overlay" id="modal-overlay">
    <div class="modal-content">
        <button type="button" onclick="closeForm()" class="absolute -top-3 -right-3 bg-red-500 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <h3 id="modal-title" class="text-center text-xl text-[#0099CC] font-bold mb-4">Souscrire à une offre</h3>
        <form action="{{ route('send.email') }}" method="POST" id="offerForm">
            @csrf
            <div class="space-y-4">
                <input type="text" name="name" id="input-name" placeholder="Votre nom" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0099CC] shadow-sm">
                <input type="email" name="email" placeholder="Votre email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0099CC] shadow-sm">
                <input type="hidden" id="inputPlan" name="offre">
                <input type="hidden" id="inputPrice" name="abonnement">
                <input type="hidden" id="inputFull" name="paiement_unique">
                <input type="hidden" name="object_message" value="Demande d'abonnement à une offre">

                <div class="radio-group bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-700 mb-2 font-medium">Mode de paiement :</p>
                    <label class="radio-option flex items-center mb-2 cursor-pointer hover:bg-gray-100 p-2 rounded transition-colors">
                        <input type="radio" name="paiement" value="Abonnement mensuel" required class="mr-2 h-4 w-4 text-[#0099CC]">
                        <span>Abonnement mensuel</span>
                    </label>
                    <label class="radio-option flex items-center cursor-pointer hover:bg-gray-100 p-2 rounded transition-colors">
                        <input type="radio" name="paiement" value="Paiement en une fois" class="mr-2 h-4 w-4 text-[#0099CC]">
                        <span>Paiement en une fois</span>
                    </label>
                </div>

                <textarea name="message" placeholder="Détails supplémentaires" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0099CC] shadow-sm"></textarea>
                <div class="modal-actions flex gap-3">
                    <button type="submit" class="flex-1 bg-[#0099CC] hover:bg-[#007EA6] text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-md">Envoyer ma demande</button>
                    <button type="button" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg transition-colors" onclick="closeForm()">Annuler</button>
                </div>
            </div>
        </form>
        <div id="error-message" class="hidden bg-red-500 text-white p-3 rounded mt-3"></div>
        <div id="success-message" class="hidden bg-green-500 text-white p-3 rounded mt-3"></div>
    </div>
</div>
</main>

<x-footer />

<script>
    function openForm(buttonElement) {
        // Récupérer les données du plan depuis l'élément parent
        const card = buttonElement.closest('.pricing-card');
        const planName = card.getAttribute('data-plan');
        const planPrice = card.getAttribute('data-price');
        const planFull = card.getAttribute('data-full');

        // Mettre à jour les champs du formulaire
        document.getElementById('inputPlan').value = planName;
        document.getElementById('inputPrice').value = planPrice;
        document.getElementById('inputFull').value = planFull;

        // Mettre à jour le titre du modal
        document.getElementById('modal-title').textContent = planName === 'Site sur mesure' ?
            'Demander un devis' : `Souscrire à l'offre ${planName}`;

        // Afficher le modal
        const modal = document.querySelector('.modal-overlay');
        modal.classList.add('active');

        // Focus sur le premier champ du formulaire
        if (document.getElementById('input-name')) {
            document.getElementById('input-name').focus();
        }
    }

    function closeForm() {
        // Masquer le modal
        const modal = document.querySelector('.modal-overlay');
        modal.classList.remove('active');

        // Réinitialiser le formulaire après un court délai
        setTimeout(() => {
            if (document.getElementById('offerForm')) {
                document.getElementById('offerForm').reset();
            }
        }, 300);
    }

    // Fermer le modal lorsqu'on clique en dehors
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.querySelector('.modal-overlay');
        const modalContent = document.querySelector('.modal-content');

        if (modal && modalContent) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeForm();
                }
            });

            // Fermer le modal avec la touche Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.classList.contains('active')) {
                    closeForm();
                }
            });
        }
    });
</script>