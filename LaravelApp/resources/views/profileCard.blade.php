@include('header')


<div class="container emp-profile">
    <div class="row">
        <div class="col-md-4">
            <div class="profile-img">
                @if (count($actor->thumbnail) > 0)
                    <img width="250" class="blurred" height="350" id="{{$actor->id . '_' . $actor->thumbnail[0]->type . '.jpg' }}"
                        src="{{ asset('servedImages/' . $actor->id . '_' . $actor->thumbnail[0]->type . '.jpg') }}" />
                        {{-- src="https://upload.wikimedia.org/wikipedia/en/a/a4/Hide_the_Pain_Harold_%28Andr%C3%A1s_Arat%C3%B3%29.jpg" /> --}}
                    @else
                    <img width="250" height="350" src="{{ asset('no-image.png') }}" />
                @endif

            </div>
            <br>
            <button style="margin-left: 35%;" class="btn btn-secondary" type="button" onclick="toggleBlur(`{{ $actor->id . '_' . $actor->thumbnail[0]->type . '.jpg'  }}`)">Toggle Blur</button>
        </div>
        <div class="col-md-6">
            <div class="profile-head">
                <h3>
                    {{ $actor->name }}
                </h3>
                <h6>
                    Aliases: {{ implode(',', $actor->aliases) }}
                </h6>
                <p class="proile-rating">World Status : <span>{{ $actor->wlStatus }}</span>
                    &nbsp; &nbsp; License : <span>{{ $actor->license }}</span>
                </p>
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#home">Attributes</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu1">Stats</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu2">Thumbnails</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <a href="/?page={{ $page }}"> <input type="button" class="profile-edit-btn" name="btnAddMore" value="Go Back" /> </a>
        </div>
        <div class="col-md-8">

            <div class="tab-content profile-tab">
                <div id="home" class="tab-pane fade in active">
                    <p class="proile-rating">Hair Color :
                        <span>{{ $actor->attributes->hairColor ?? 'no color' }}</span>
                    </p>
                    <p class="proile-rating">Ethnicity :
                        <span>{{ $actor->attributes->ethnicity ?? 'no ethnicity' }}</span>
                    </p>
                    <p class="proile-rating">Tattoos : <span>{{ $actor->attributes->tattoos }}</span> </p>

                    <p class="proile-rating">Piercings : <span>{{ $actor->attributes->piercings }}</span> </p>
                    <p class="proile-rating">Breast Size :
                        <span>{{ $actor->attributes->breastSize ?? 'no breast size' }}</span>
                    </p>
                    <p class="proile-rating">Breast Type :
                        <span>{{ $actor->attributes->breastType ?? 'no breast type' }}</span>
                    </p>

                    <p class="proile-rating">Gender : <span>{{ $actor->attributes->gender }}</span> </p>
                    <p class="proile-rating">Orientation : <span>{{ $actor->attributes->orientation }}</span> </p>
                    <p class="proile-rating">Age : <span>{{ $actor->attributes->age ?? 'no age' }}</span> </p>
                </div>

                <div id="menu1" class="tab-pane fade">
                    <p class="proile-rating">Subscriptions :
                        <span>{{ $actor->attributes->stats->subscriptions }}</span>
                    </p>
                    <p class="proile-rating">Monthly Searches :
                        <span>{{ $actor->attributes->stats->monthlySearches }}</span>
                    </p>
                    <p class="proile-rating">Views : <span>{{ $actor->attributes->stats->views }}</span> </p>

                    <p class="proile-rating">Videos Count :
                        <span>{{ $actor->attributes->stats->videosCount }}</span>
                    </p>
                    <p class="proile-rating">Premium Videos Count :
                        <span>{{ $actor->attributes->stats->premiumVideosCount }}</span>
                    </p>
                    <p class="proile-rating">White Label Video Count :
                        <span>{{ $actor->attributes->stats->whiteLabelVideoCount }}</span>
                    </p>

                    <p class="proile-rating">Rank : <span>{{ $actor->attributes->stats->rank }}</span> </p>
                    <p class="proile-rating">Rank Premium :
                        <span>{{ $actor->attributes->stats->rankPremium }}</span>
                    </p>
                    <p class="proile-rating">Rank World : <span>{{ $actor->attributes->stats->rankWl }}</span> </p>
                </div>

                <div id="menu2" class="tab-pane fade">

                    <div class="row">
                        @foreach ($actor->thumbnail as $th)
                            <div class="column">
                                <img width="{{ $th->width }}" height="{{ $th->height }}"
                                    {{-- src="{{ asset('servedImages/' . $actor->id . '_' . $th->type . '.jpg') }}" /> --}}
                                    src="https://upload.wikimedia.org/wikipedia/en/a/a4/Hide_the_Pain_Harold_%28Andr%C3%A1s_Arat%C3%B3%29.jpg" />
                                {{-- <img width="150" height="250" src="{{ $th->urls }}" /> --}}
                            </div>
                        @endforeach
                    </div>
                    <br>

                    <div class="row">
                        @foreach ($actor->thumbnail as $th)
                            <div class="column">
                                <p>{{ $th->type }}</p>
                            </div>
                        @endforeach
                    </div>

                </div>

            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            {{-- <div class="profile-work"> <p>PORNHUB LINK</p> --}}
                {{-- <a target="_blank" href="{{ $actor->link }}">Click here</a><br /> --}}
            {{-- </div> --}}
        </div>

    </div>
</div>




</main>

<footer>
    <span class="text-body-secondary"> © 2024 Copyright: <a class="text-reset fw-bold" href="https://www.linkedin.com/in/paraskevas-lysikatos/"> LysikatosParaskevas</a></span>
</footer>

</body>

<script>
    // JavaScript function to toggle the class of the image
    function toggleBlur(id) {
        console.log(id);
        var element = document.getElementById(id) ;
        console.log(element.classList);
        element.classList.toggle("blurred");
      // console.log(element.classList);
    }
  </script>

</html>
