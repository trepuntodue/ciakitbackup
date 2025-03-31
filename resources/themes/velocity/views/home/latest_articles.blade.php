<div class="vetrina-blog full-content-wrapper bglight my-8 overflow-hidden px-4 sm:my-0 sm:py-4">

  <div class="container mx-auto">
    <h2 class="my-4 px-8 text-xl sm:px-0">Ultimi articoli dal blog:</h2>
  </div>

  <div class="vetrina-wrapper">

    {{-- @php
      var_dump(array_slice($blogArticles['articles'], 0, 3));
    @endphp --}}

    @foreach (array_slice($blogArticles['articles'], 0, 3) as $article)
      <div class="article-wrapper position-relative">

        <div class="shadow"></div>
        @php
          $featuredMediaId = $article->featured_media;
          $featuredMedia = collect($blogArticles['medias'])->firstWhere('id', $featuredMediaId);
          $imageUrl = $featuredMedia ? $featuredMedia->source_url : '';
        @endphp

        @if ($imageUrl)
          <img src="{{ $imageUrl }}" alt="{{ $article->link }}">
        @endif

        <a class="position-absolute w-100 h-100" style="z-index:100;" target="_blank" href="{{ $article->link }}">
        </a>
        <div class="content">
          <h3>{{ $article->title->rendered }}</h3>
          <p>{!! $article->excerpt->rendered !!}</p>
          <a target="_blank" href="{{ $article->link }}">Leggi tutto</a>
        </div>
      </div>
    @endforeach

  </div>
</div>
