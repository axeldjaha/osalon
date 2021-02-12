<!doctype html>
<html lang="fr">

<head>
    <title>{{config("app.name")}} :: Nouveau mot de passe</title>
    <link href="{{asset("css/login.css")}}" rel="stylesheet" id="bootstrap-css">
    <link href="{{asset("css/appstyle.css")}}" rel="stylesheet" id="bootstrap-css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>

<body class="bg-light">

<div class="container">
    <div class="card card-container border-0">
        <a class="text-center m-auto" href="{{config('app.url')}}">
            <img class="img-fluid" style="width: 45%" src="{{asset("images/logo.png")}}" alt="" />
        </a>
        <p id="profile-name" class="profile-name-card"></p>
        <div class="mt-2 mb-4 text-center">
            <h5 class="text-dark">Nouveau mot de passe</h5>
            <small class="text-muted">Renseignez votre nouveau mot de passe puis validez</small>
        </div>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                Le lien de réinitialisation du mot de passe vous a été envoyé par email!
            </div>
        @endif
        <form class="form-signin" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Confirmer</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button class="btn btn-lg btn-primary text-light btn-wide" type="submit" style="font-size: inherit; height: inherit">
                Valider
            </button>
        </form>
    </div>
</div>
</body>
</html>
