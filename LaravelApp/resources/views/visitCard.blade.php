<div class="row row-cols-1 row-cols-md-5">

    @foreach ($actors as $a)
    <div class="col mb-2">
        <div class="card h-100">
            <img width="250" height="150" src="  @if(count($a->thumbnail)>0){{ $a->thumbnail[0]->urls}} @endif  " class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">{{ $a->name }}</h5>
                {{-- only 4 aliases otherwise will break my page footer --}}
                <p class="card-text">Aliases: {{ implode(',', array_slice($a->aliases,0,3 ))}}</p>
            </div>
        </div>
    </div>
    @endforeach


</div>

{{ $actors->links() }}


