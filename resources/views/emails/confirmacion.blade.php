<html>
<head>
    <meta charset="utf-8">
    <title>Nueva confirmación de asistencia</title>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de asistencia</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
            font-family: 'Montserrat', Arial, sans-serif;
            color: #22223b;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #fff;
            max-width: 500px;
            margin: 48px auto;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(56, 178, 172, 0.10);
            padding: 40px 28px 32px 28px;
            text-align: center;
        }
        .logo {
            width: 90px;
            height: 90px;
            margin-bottom: 18px;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(56,178,172,0.10);
        }
        .brand {
            font-size: 28px;
            font-weight: 700;
            color: #5f6caf;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        .title {
            font-size: 24px;
            margin: 28px 0 16px 0;
            color: #22223b;
            font-weight: 700;
        }
        .info {
            text-align: left;
            font-size: 19px;
            margin: 0 auto 22px auto;
            max-width: 370px;
            background: #f1f5f9;
            border-radius: 10px;
            padding: 22px 24px 16px 24px;
            box-shadow: 0 2px 8px rgba(56,178,172,0.04);
        }
        .info strong {
            color: #5f6caf;
        }
        .acomp-list {
            margin: 0 0 12px 0;
            font-size: 17px;
            padding-left: 20px;
            text-align: left;
        }
        .acomp-list li {
            margin-bottom: 4px;
            font-size: 15px;
        }
        .restr {
            background: #fff3cd;
            color: #b7791f;
            border-radius: 7px;
            padding: 10px 14px;
            margin: 14px 0 0 0;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 17px;
        }
        .restr-icon {
            font-size: 28px;
            margin-right: 8px;
        }
        .footer {
            margin-top: 36px;
            font-size: 13px;
            color: #a0aec0;
        }
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 24px 0 18px 0;
        }
    </style>
</head>
<body>
    <div class="container">
    <img src="https://cdn-icons-png.flaticon.com/512/4642/4642423.png" alt="Logo" class="logo">
        <div class="brand">🎉 ¡Nueva confirmación de asistencia!</div>
        <div class="info">
            <p><strong>Nombre:</strong> {{ $invitado->nombre }}</p>
            <p><strong>Teléfono:</strong> {{ $invitado->telefono }}</p>
            <p><strong>Bebida Cena:</strong> {{ $invitado->bebida_cena ?? '-' }}</p>
            <p><strong>Bebida Fiesta:</strong> {{ $invitado->bebida_fiesta ?? '-' }}</p>
            @if (!empty($invitado->invitados_adicionales))
                <p style="margin-bottom:6px;"><strong>Acompañantes:</strong></p>
                <ul class="acomp-list">
                    @foreach ($invitado->invitados_adicionales as $a)
                        <li>
                            👤 {{ $a['nombre'] ?? '-' }}
                            @if(isset($a['nino']) && $a['nino']) <span style="color:#ed8936;">(Niño/a)</span> @endif<br>
                            <span style="margin-left:24px;">
                                <strong>Bebida Cena:</strong> {{ $a['bebida_cena'] ?? '-' }}<br>
                                <strong>Bebida Fiesta:</strong> {{ $a['bebida_fiesta'] ?? '-' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
            @if ($invitado->restricciones)
                <div class="restr">
                    <span class="restr-icon">⚠️</span> </br>

                    <span><strong>Restricciones alimenticias:</strong> {{ $invitado->restricciones }}</span>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
