@extends("email.layout")

@section("content")

    <tr>
        <td style="word-break:break-word;font-size:0px;padding:0px;padding-bottom:30px" align="center">
            <div style="font-family:Open Sans,Helvetica,Arial,sans-serif;font-size:20px;line-height:22px">
                <br>
                Votre code pour changer de mot de passe
            </div>
        </td>
    </tr>

    <tr>
        <td style="word-break:break-word;font-size:0px;padding-bottom:30px; line-height: 0" align="center">
            <div style="color:#2D4197;font-family:Open Sans,Helvetica,Arial,sans-serif;font-size:28px;font-weight:normal;line-height:22px">
                <strong style="color: #f47922">{{$code}}</strong>
            </div>
        </td>
    </tr>

    <tr>
        <td style="word-break:break-word;font-size:0px;padding:0px" align="center">
            <div style="color:#8c8c8c;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:14px;line-height:22px">
                <p style="font-size:12px;line-height:18px">
                    Vous recevez ce email car vous avez demandé un code pour changer de mot de passe.
                    Si vous n'avez pas demandé de code,
                    veuillez ignorer cet email et votre mot de passe restera le même.
                    <br>
                    <br>
            </div>
        </td>
    </tr>

@endsection
