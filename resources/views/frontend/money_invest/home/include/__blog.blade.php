<section class="container py-5 my-md-3 my-lg-2 my-xl-4 my-xxl-5">
    <div class="d-md-flex align-items-center pb-3 mb-md-3 mb-lg-4 mt-2 mt-sm-3 pt-2 pt-sm-4 pt-lg-5">
        <h2 class="h1 text-center text-md-start mb-4 mb-md-0 me-md-4">{{ $data['blog_title_small'] }}</h2>
    </div>
    <div class="swiper row"
        data-swiper-options='
      {
        "spaceBetween": 24,
        "pagination": {
          "el": ".swiper-pagination",
          "clickable": true
        },
        "breakpoints": {
          "576": { "slidesPerView": 2 },
          "992": { "slidesPerView": 3 }
        }
      }
    '>

        <!-- Article -->
        @foreach (\App\Models\Blog::where('locale', app()->getLocale())->latest()->take(3)->get() as $blog)
            <div class="swiper-wrapper col-lg-4">
                <article class="swiper-slide">
                    <a href="#">
                        <img class="rounded-5" src="{{ asset($blog->cover) }}" alt="Image">
                    </a>
                    <h3 class="h4 pt-4">
                        <a href="{{ route('blog-details', $blog->id) }}">{{ $blog->title }}</a>
                    </h3>
                    <p{!! Str::limit($blog->details, 100) !!}</p>
                        <div class="d-flex flex-wrap align-items-center pt-1 mt-n2">
                            <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                            <span class="fs-sm text-body-secondary mt-2">{{ $blog->created_at }}</span>
                            <span class="fs-xs opacity-20 mt-2 mx-3">|</span>
                            <a class="badge bg-primary bg-opacity-10 text-primary fs-xs mt-2"
                                href="#">Analytics</a>
                        </div>
                </article>
            </div>
        @endforeach
        <div class="swiper-pagination position-relative bottom-0 mt-2 pt-4 d-lg-none"></div>
    </div>
</section>
