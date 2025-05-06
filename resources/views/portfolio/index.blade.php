<x-header title="Portfolio" />
<section class="portfolio-header">
    <h1>Nos Dernières Réalisations</h1>
    <p>Voici quelques-uns des sites que nous avons fièrement créés pour nos clients.</p>
</section>

<section class="portfolio-gallery">
    @if(count($portfolioItems) > 0)
        @foreach($portfolioItems as $item)
            <div class="portfolio-item">
                @if($item->isImage())
                    <img src="{{ asset($item->path) }}" alt="{{ $item->title }}" class="w-full h-auto">
                @else
                    <video autoplay muted loop playsinline poster="{{ asset($item->path) }}">
                        <source src="{{ asset($item->path) }}" type="video/mp4">
                        Votre navigateur ne supporte pas la vidéo HTML5.
                    </video>
                @endif
                <div class="portfolio-caption">
                    <h3>{{ $item->title }}</h3>
                    <p>{{ $item->description }}</p>
                    @if($item->technology)
                        <div class="portfolio-technologies">
                            <strong>Technologies:</strong> {{ $item->technology }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="portfolio-empty">
            <p>Aucun projet à afficher pour le moment. Revenez bientôt !</p>
        </div>
    @endif
</section>
<x-footer />