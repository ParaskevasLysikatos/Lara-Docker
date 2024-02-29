<div class="row row-cols-1 row-cols-md-5">


    @foreach ($actors as $a)
        <div class="col mb-2">
            <form action="profile/{{ $a->id }}" method="GET" ondblclick="this.submit()">
                <div class="card h-100" id="hoverCard">

                    @if (count($a->thumbnail) > 0)
                        <img class="card-img-top" width="250" height="180" id="{{ $servedImages[$loop->index] }}"
                            {{-- src="{{ asset('servedImages/' . $servedImages[$loop->index]) }}" /> --}}
                            src="https://upload.wikimedia.org/wikipedia/en/a/a4/Hide_the_Pain_Harold_%28Andr%C3%A1s_Arat%C3%B3%29.jpg" />
                    @else
                        <img class="card-img-top" width="250" height="180" src="{{ asset('no-image.png') }}" />
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $a->name }}</h5>
                        {{-- only 3 aliases otherwise will break my page footer --}}
                        <p class="card-text">Aliases: {{ implode(',', array_slice($a->aliases, 0, 2)) }}</p>
                        <button onclick="toggleBlur()">Toggle Blur</button>
                    </div>
                </div>
                <input type="hidden" name="page" value="{{ request('page') }}" />
            </form>

        </div>
    @endforeach

    {{ $actors->links() }}
</div>


