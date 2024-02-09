@include('header')

        <section class="py-3 text-center container">
            <div class="row py-lg-3">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">Welcome to List of profiles, last updated:{{ $currentDate }}</h1>
                    <p class="lead text-body-secondary">Visit every profile for more info by clicking on the corresponding card.</p>
                </div>
            </div>
        </section>

            <div class="container">

                @include('visitCard')

            </div>

  @include('footer')


