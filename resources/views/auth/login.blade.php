<!doctype html>
<html lang="fr">

<head>
    <title>{{config("app.name")}} :: Connexion</title>
    <link href="{{asset("css/login.css")}}" rel="stylesheet" id="bootstrap-css">
    <link href="{{asset("css/appstyle.css")}}" rel="stylesheet" id="bootstrap-css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
</head>

<body class="bg-light">

<div class="container">
    <div class="card card-container border-0">
        <a class="text-center m-auto" href="{{config('app.url')}}">
            <img class="img-fluid" style="width: 45%" src="{{asset("images/logo.png")}}" alt="" />
        </a>
        <p id="profile-name" class="profile-name-card"></p>
        <form class="form-signin" method="POST" action="{{ route('login') }}">
            @csrf
            <span id="reauth-email" class="reauth-email"></span>
            <input name="email" type="email" id="inputEmail" class="form-control @error("email") is-invalid @enderror" placeholder="Email" required value="{{old("email")}}">
            @error("email") <div class="invalid-feedback mb-2"><strong>{{$message}}</strong></div> @enderror
            <input name="password" type="password" id="inputPassword" class="form-control @error("password") is-invalid @enderror" placeholder="Mot de passe" required>
            @error("password") <div class="invalid-feedback"><strong>{{$message}}</strong></div> @enderror
            <div id="remember" class="checkbox mt-2 mb-2">
                <label class="text-muted">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Se souvenir de moi
                </label>
            </div>
            <button class="btn btn-lg bg-primary btn-wide text-light" type="submit" style="font-size: inherit; height: inherit">Connexion</button>
        </form><!-- /form -->
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-password">
                Mot de passe oublié ?
            </a>
        @endif
    </div>
</div>
</body>
</html>
