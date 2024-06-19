<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
</head>

<style>
    @media screen and (max-width: 420px) {
        .content {
            width: 100% !important;
            display: block !important;
            padding: 10px !important;
        }

        .header,
        .body,
        .footer {
            padding: 20px !important;
        }
    }
</style>

<body style="font-family: 'Poppins', Arial, sans-serif">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding: 20px;">
                <table class="content" width="600" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 1px solid #cccccc;">
                    <!-- Header -->
                    <tr>
                        <td class="header" style="background-color: #ededed; padding: 20px; text-align: center; color: white; font-size: 24px;">
                            @php
                            $img = Storage::disk('r2')->url('logo_uch.png');
                            @endphp
                            <img src="{{$img}}" style="max-width: 250px;" alt="">
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="body" style="padding: 40px; text-align: left; font-size: 16px; line-height: 1.6;">
                            Hola<br>
                            Para restablecer su contraseña, haga clic en el siguiente botón:
                        </td>
                    </tr>

                    <!-- Call to action Button -->
                    <tr>
                        <td style="padding: 0px 40px 0px 40px; text-align: center;">
                            <!-- CTA Button -->
                            <table cellspacing="0" cellpadding="0" style="margin: auto;">
                                <tr>
                                    <td align="center" style="background-color: #345C72; padding: 10px 20px; border-radius: 5px;">
                                        <a href="{{ route('reset-password', ['token' => $token]) }}" target="_blank" style="color: #ffffff; text-decoration: none; font-weight: bold;">Recuperar Contraseña</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="body" style="padding: 40px; text-align: left; font-size: 16px; line-height: 1.6;">
                            <span style="font-weight: bold; color: #c53333;">Recuerda:<br>
                                No comparta su contraseña con otra persona. <br>
                                Asegurése en cambiar la contraseña luego de iniciar sesión.
                            </span>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td class="footer" style="background-color: #333333; padding: 40px; text-align: center; color: white; font-size: 14px;">
                            Copyright &copy; {{ date("Y") }} | SISGESINV
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>