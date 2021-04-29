<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="fr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>{{ $title ?? config("app.name") . " | Administration" }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"/>
<meta name="description" content="{{ config("app.name") }} - administration">
<!-- Disable tap highlight on IE -->
<meta name="msapplication-tap-highlight" content="no">

<!-- jQuery library -->
<script src="{{asset("js/jquery-3.5.0.min.js")}}"></script>

<link href="{{asset('main.css')}}" rel="stylesheet">

<link href="{{asset('css/style.css')}}" rel="stylesheet">

<script type="text/javascript" src="https://cdn.datatables.net/s/dt/dt-1.10.10,se-1.1.0/datatables.min.js"></script>
<link type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css" rel="stylesheet" />
<script type="text/javascript" src="{{asset("js/dataTables.checkboxes.min.js")}}"></script>

@yield("extension")
