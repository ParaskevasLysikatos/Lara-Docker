<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>


    </head>
    <body>
        <div>

            <div class="container p-2 m-3">
                @foreach ($users as $user)
                    {{ $user->name }}
                    <hr>
                    <br>
                @endforeach
            </div>

            {{ $users->links() }}

        </div>
    </body>
</html>
