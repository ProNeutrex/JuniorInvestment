<main class="page-wrapper">
    <header class="navbar navbar-expand-lg fixed-top bg-light">
        <div class="container">
            <a class="navbar-brand pe-sm-3" href="/">
                <img class="logo-unfold" src="{{ asset(setting('site_logo', 'global')) }}" alt="Logo" />
            </a>
            <div class="form-check form-switch mode-switch order-lg-2 me-3 me-lg-4 ms-auto" data-bs-toggle="mode">
              <input class="form-check-input" type="checkbox" id="theme-mode">
              <label class="form-check-label" for="theme-mode">
                <i class="ai-sun fs-lg"></i>
              </label>
              <label class="form-check-label" for="theme-mode">
                <i class="ai-moon fs-lg"></i>
              </label>
            </div>
            <a class="btn btn-primary btn-sm fs-sm order-lg-3 d-none d-sm-inline-flex" href="/login" target="_blank"
                rel="noopener">
                <i class="fa-solid fa-right-to-bracket"></i> MINHA CONTA
            </a>
            <button class="navbar-toggler ms-sm-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <nav class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav navbar-nav-scroll me-auto" style="--ar-scroll-height: 520px;">
                    @foreach ($navigations as $navigation)
                        @if ($navigation->page->status || $navigation->page_id == null)
                            <li class="nav-item">
                                <a class="nav-link @if (url($navigation->url) == Request::url()) active @endif"
                                    href="{{ url($navigation->url) }}">{{ $navigation->tname }}</a>
                            </li>
                        @endif
                    @endforeach
                    
                </ul>
                <div class="dropdown nav d-none d-sm-block order-lg-3">
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                          data-bs-auto-close="outside" aria-expanded="false">Lang</a>
                      <ul class="dropdown-menu">
                          @foreach (\App\Models\Language::where('status', true)->get() as $lang)
                              <li><a class="dropdown-item"
                                      href="{{ route('language-update', ['name' => $lang->locale]) }}">{{ $lang->name }}</a>
                              </li>
                          @endforeach
                      </ul>
                  </li>
                  </div>
                <div class="d-sm-none p-3 mt-n3">
                    <a class="btn btn-primary w-100 mb-1" href="/login" target="_blank" rel="noopener">
                        <i class="ai-cart fs-xl me-2 ms-n1"></i>
                        MINHA CONTA
                    </a>
                </div>
            </nav>
        </div>
    </header>
</main>
