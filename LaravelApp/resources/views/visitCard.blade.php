<div class="row row-cols-1 row-cols-md-5">


    @foreach ($actors as $a)
        <div class="col mb-2">
            <form action="profile/{{ $a->id }}" method="GET" onclick="this.submit()">
                <div class="card h-100" id="hoverCard">

                    @if (count($a->thumbnail) > 0)
                        <img class="card-img-top" width="250" height="180"
                            src="{{ asset('servedImages/' . $servedImages[$loop->index]) }}" />
                    @else
                        <img class="card-img-top" width="250" height="180" src="{{ asset('no-image.png') }}" />
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $a->name }}</h5>
                        {{-- only 3 aliases otherwise will break my page footer --}}
                        <p class="card-text">Aliases: {{ implode(',', array_slice($a->aliases, 0, 2)) }}</p>
                    </div>
                </div>
                <input type="hidden" name="page" value="{{ request('page') }}" />
            </form>

        </div>
    @endforeach


</div>

{{ $actors->links() }}
