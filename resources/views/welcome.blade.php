<x-header title="Home" />
<main class="site-content">
  <section class="hero">
    <div class="hero-content">
      <h1 class="fancy-title">
        <div class="word" id="word-kreyatik"></div>
        <div class="word" id="word-studio"></div>
      </h1>
      <p>Votre lumière mérite d'être en ligne.</p>
      <p>Faites décoller votre présence en ligne dès <span class="font-bold">49 € / mois.</span> Design, performance et accompagnement inclus.</p>
      <a href="#engagements" class="cta-button ">Nos Engagements</a>
    </div>
  </section>

  <!-- ========== À PROPOS (STORYTELLING + PARALLAX) ========== -->
  <section class="about-parallax">
    <div class="about-parallax-overlay break-words max-w-full px-4">
      <div class="container px-0">
        <h2 class="section-title text-white text-center text-xl sm:text-2xl md:text-3xl leading-tight break-words max-w-full">
          Des Kréyations qui laissent une empreinte
        </h2>
        <p class="lead text-white text-center text-base md:text-lg">
          Chaque pixel, chaque ligne de code, chaque interaction que nous créons a un but : faire briller votre identité en ligne.
        </p>
        <div class="mt-8 text-white">
          <div class="flex flex-wrap justify-center max-w-4xl mx-auto">
            <div class="w-full md:w-1/2 px-4 mb-6 md:mb-0">
              <p class="text-sm md:text-base">
                Chez Kréyatik Studio, nous croyons que la vraie force du digital réside dans l'humain. Nous ne vendons pas juste des sites web.
                Nous concevons des expériences, des émotions et des leviers de croissance sur-mesure.
              </p>
              <p class="text-sm md:text-base mt-4">
                🚀 Que vous lanciez votre activité ou que vous vouliez franchir un cap, nous sommes là pour vous accompagner à chaque étape.
              </p>
            </div>
            <div class="w-full md:w-1/2 px-4">
              <div class="about-highlights text-white">
                <h4 class="mb-3 text-base md:text-lg">Notre ADN :</h4>
                <ul class="about-list text-sm md:text-base space-y-2">
                  <li>🌟 Créativité poussée, pensée stratégique</li>
                  <li>🛠️ Technologies modernes & stables</li>
                  <li>👥 Écoute active et collaboration continue</li>
                  <li>💡 Propositions qui ont du sens, pas du vent</li>
                  <li>🔁 Amélioration continue, même après livraison</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== ENGAGEMENTS ========== -->
  <section class="engagements py-5" id="engagements">
    <div class="container">
      <h2 class="section-title">Pourquoi choisir Kréyatik Studio ?</h2>
      <p class="lead text-center">Plus qu'un prestataire, un véritable partenaire digital à vos côtés.</p>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mt-8 mb-4 ">
        <div>
          <div class="engagement-box">
            <h4>📐 Design sur-mesure</h4>
            <p>Un site qui vous ressemble. Du logo aux micro-interactions, chaque élément reflète votre ADN de marque.</p>
          </div>
        </div>
        <div>
          <div class="engagement-box">
            <h4>📱 Responsive & rapide</h4>
            <p>Des performances optimales sur tous les écrans. Mobile first, avec un temps de chargement ultra optimisé.</p>
          </div>
        </div>
        <div>
          <div class="engagement-box">
            <h4>🌱 Hébergement sécurisé & écologique</h4>
            <p>Hébergement sécurisé, éco-responsable avec sauvegardes automatiques et compensation CO₂. Support réactif pour plus de sérénité.</p>
          </div>
        </div>
        <div>
          <div class="engagement-box">
            <h4>🔍 SEO ready</h4>
            <p>Code optimisé pour le référencement naturel. Votre site est pensé pour grimper sur Google.</p>
          </div>
        </div>
        <div>
          <div class="engagement-box">
            <h4>🤝 Suivi humain</h4>
            <p>Un interlocuteur dédié, à l'écoute de vos besoins, du premier brief à bien après la mise en ligne.</p>
          </div>
        </div>
        <div>
          <div class="engagement-box">
            <h4>🎯 Résultats concrets</h4>
            <p>Un site qui convertit, qui attire, et qui sert vos objectifs business. On ne parle pas design, on parle impact.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ========== CONTACT SECTION ========== -->
  <section id="contact" class="contact-section">
    <div class="contact-container">
      <h2 class="contact-title">Contactez-nous</h2>

      <div id="form-message" class="hidden mt-5 text-[#00A86B] font-bold">
        Merci ! Votre message a bien été envoyé.
      </div>

      <form action="{{ route('send.email') }}" method="post" class="contact-form" id="contactForm">
        @csrf
        <input type="text" name="name" placeholder="Votre nom" required class="contact-input">
        <input type="email" name="email" placeholder="Votre email" required class="contact-input">
        <input type="text" name="object_message" placeholder="Objet" required class="contact-input">
        <textarea name="message" placeholder="Votre message" rows="6" required class="contact-textarea"></textarea>
        <button type="submit" class="contact-button">Envoyer</button>
      </form>
      <div>
        <div id="error-message" class="hidden bg-red-500 text-white p-3 rounded mt-3"></div>
        <div id="success-message" class="hidden bg-green-500 text-white p-3 rounded mt-3"></div>
      </div>
    </div>
  </section>
</main>
<x-footer />