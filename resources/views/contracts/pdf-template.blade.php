<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registratie Zakelijke Gebruiker</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.5; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 20px; }
        .section { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="title">Zakelijke registratie</div>

    <div class="section">
        <strong>Naam:</strong> {{ $user->name }}
    </div>

    <div class="section">
        <strong>Email:</strong> {{ $user->email }}
    </div>

    <div class="section">
        <strong>Registratiedatum:</strong> {{ $user->created_at->format('d-m-Y') }}
    </div>

</body>
</html>
