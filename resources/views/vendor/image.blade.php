<html style="height: 100%;">

<head>
    <title>{{ \Illuminate\Support\Str::slug($salon->nom . "-" . date("d-m-Y", strtotime($sms->created_at))) }} </title>

    <meta name="viewport" content="width=device-width, minimum-scale=0.1">
    <!-- jQuery library -->
    <script src="{{asset("js/jquery-3.5.0.min.js")}}"></script>

    <link href="{{asset('main.css')}}" rel="stylesheet">

    <link href="{{asset('css/style.css')}}" rel="stylesheet">

    <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
</head>

<body style="margin: 0px; background: #fafafa;">

<div class="bg-orange p-2">
    <p class="font-size-md mb-0 text-center text-white"><strong>Défilez pour voir les images</strong></p>
</div>
<div class="row m-0">

    @foreach($sms->lien->images as $image)
        <div class="col-md-auto mb-2">
            <img class="img-fluid" src="{{ asset("files") . "/" . $image->nom }}" alt="image-{{ $image->id }}">
        </div>
    @endforeach
</div>

</body>

</html>
