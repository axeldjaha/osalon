<!doctype html>
<html lang="fr">

<head>
    <title>{{config("app.name")}} :: Récupération de mot de passe</title>
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
            <h5 class="text-dark">Réinitialisation de mot de passe</h5>
            <small class="text-muted">Entrez votre email pour recevoir le lien de réinitialisation</small>
        </div>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                Le lien de réinitialisation du mot de passe vous a été envoyé par email!
            </div>
        @endif
        <form class="form-signin" method="POST" action="{{ route('password.email') }}">
            @csrf
            <span id="reauth-email" class="reauth-email"></span>
            <input name="email" type="email" id="inputEmail" class="form-control mb-3 @error("email") is-invalid @enderror" placeholder="Email address" required value="{{old("email")}}">
            @error("email") <div class="invalid-feedback mb-3"><strong>{{$message}}</strong></div> @enderror
            <button class="btn btn-lg btn-primary text-light btn-wide" type="submit" style="font-size: inherit; height: inherit">
                Envoyer le lien
            </button>
        </form>
    </div>
</div>
</body>
</html>
