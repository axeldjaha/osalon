<html style="height: 100%;">

<head>
    <title>{{ \Illuminate\Support\Str::slug($salon->nom . "-" . date("d-m-Y", strtotime($sms->created_at))) }} </title>
    <meta name="viewport" content="width=device-width, minimum-scale=0.1">
    @include("layouts.head")
    <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
</head>

<body style="margin: 0px; background: #0e0e0e; height: 100%">
<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" src="https://i.ibb.co/br4trDh/Fire-Shot-Capture-010-Paiement-de-scolarit-ecolepro-net.png" alt="First slide">
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="https://i.ibb.co/brZ9wjr/device-2021-02-22-033330.png" alt="Second slide">
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="https://i.ibb.co/nQBTjRn/Fire-Shot-Capture-007-El-ves-ecolepro-net.png" alt="Second slide">
        </div>

    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
</body>

</html>
