<section class="bg-secondary pt-5 pb-5">
    <div class="container-fluid">
      <div class="position-relative overflow-hidden">
        <div class="bg-primary position-absolute top-0 start-0 w-100 h-100" data-aos="zoom-in" data-aos-duration="600" data-aos-offset="300" data-disable-parallax-down="lg"></div>
        <div class="row align-items-center position-relative z-2">
          <div class="col-md-6 col-lg-5 col-xl-4 offset-lg-1 pb-sm-3 pt-5 py-md-0 py-lg-5" data-aos="fade-up" data-aos-duration="500" data-aos-offset="250" data-disable-parallax-down="lg">
            <h2 class="display-3 text-white text-center text-md-start mb-4 mb-lg-5">{{$data['title_small']}}</h2>
            <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-md-start">
              <a class="btn btn-light btn-lg px-3 py-2 me-sm-3 mb-3 mb-sm-0" href="{{$data['linkpdf']}}">
                <i class="fa-solid fa-file-pdf"></i>  DOWNLOAD PDF
              </a>
            </div>
          </div>
          <div class="col-md-6 col-lg-5 offset-xl-1">
            <img class="d-block d-md-none mx-auto" src="{{ asset('frontend/theme_base/padraods/img/landing/mobile-app-showcase/features/03.png') }}" width="420" alt="App screen">
            <div class="d-none d-md-block position-relative mx-auto" style="max-width: 484px;">
              <div class="position-absolute top-0 start-0 w-100 h-100" data-aos="zoom-in" data-aos-duration="400" data-aos-delay="600" data-aos-offset="300" data-aos-easing="ease-out-back" data-disable-parallax-down="lg">
                <svg class="text-warning position-absolute top-0 start-0 w-100 h-100" width="484" height="590" viewBox="0 0 484 590" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M35.0852 77.896C29.0293 78.1976 23.6248 79.9148 17.8537 81.4616C16.7267 81.7638 16.0606 82.9197 16.3605 84.0437C16.6606 85.1684 17.8209 85.8347 18.9481 85.5331C24.4203 84.0662 29.5448 82.3932 35.2968 82.1056C36.4603 82.0476 37.3589 81.0561 37.2973 79.8952C37.2427 78.7329 36.2485 77.8374 35.0852 77.896Z"></path>
                  <path d="M42.4929 62.8378C31.073 56.0023 17.3524 50.4482 4.32343 47.5959C3.18562 47.3476 2.05922 48.0685 1.81258 49.2044C1.56593 50.3402 2.28436 51.4658 3.42217 51.7141C16.0197 54.4726 29.2896 59.844 40.327 66.4552C41.3271 67.052 42.6231 66.7263 43.2192 65.7286C43.8152 64.7309 43.493 63.4346 42.4929 62.8378Z"></path>
                  <path d="M51.1742 57.6399C50.0172 48.6073 44.9377 40.4608 39.1682 33.66C38.4115 32.7739 37.0807 32.6648 36.1979 33.4154C35.3087 34.1687 35.2011 35.4998 35.9511 36.3879C41.2085 42.5807 45.9377 49.949 46.9927 58.1754C47.1402 59.3297 48.1987 60.1459 49.3501 59.9984C50.5016 59.8517 51.3216 58.7935 51.1742 57.6399Z"></path>
                </svg>
              </div>
              <img src="{{ asset('frontend/theme_base/padraods/img/pdf.png') }}" alt="App screens" data-aos="fade-left" data-aos-duration="600" data-aos-delay="200" data-aos-offset="300" data-disable-parallax-down="lg">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
