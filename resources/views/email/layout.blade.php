<div style="background:#f5f5f5">
    <div style="background-color:#f5f5f5;padding-top:80px">
        <div style="margin:0 auto;max-width:600px;background:#ffffff">
            <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#ffffff;border-top:3px solid #F47922" align="center" border="0">
                <tbody>
                <tr>
                    <td style="text-align:center;vertical-align:top;font-size:0px;padding:40px 30px 30px 30px">
                        <div aria-labelledby="mj-column-per-100" class="" style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%">
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                <tbody>
                                <tr>
                                    <td style="word-break:break-word;font-size:0px;padding-bottom: 30px;" align="center">
                                        <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px" align="center" border="0">
                                            <tbody>
                                            <tr>
                                                <td style="width:100px">
                                                    <a href="{{config("app.url")}}" target="_blank" data-saferedirecturl="#">
                                                        <img alt="" title="" height="auto" src="{{asset('images/logo.png')}}" style="border:none;display:block;outline:none;text-decoration:none;width:100%;height:auto" width="200" class="CToWUd">
                                                    </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                @yield("content")

                                <tr>
                                    <td style="word-break:break-word;font-size:0px;padding:0px;padding-bottom:35px" align="center">
                                        <div style="color:#8c8c8c;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:14px;line-height:22px"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div style="margin:0 auto;max-width:600px">
            <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%" align="center" border="0">
                <tbody>
                <tr>
                    <td style="text-align:center;vertical-align:top;font-size:0px;padding:30px">
                        <div style="vertical-align:top;display:inline-block;font-size:13px;text-align:left;width:100%">
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                <tbody>
                                <tr>
                                    <td style="word-break:break-word;font-size:0px;padding:0px;padding-bottom:15px" align="center">
                                        <div style="color:#8c8c8c;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:12px;line-height:22px">
                                            © {{date('Y')}} {{config("app.name")}}
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
    <br>
</div>
