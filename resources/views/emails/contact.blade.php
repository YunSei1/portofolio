<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; padding: 30px; }
        h1 { color: #7c3aed; font-size: 24px; margin-bottom: 20px; }
        .field { margin-bottom: 16px; }
        .label { font-weight: bold; color: #555; font-size: 12px; text-transform: uppercase; }
        .value { color: #333; margin-top: 4px; }
        hr { border: none; border-top: 1px solid #eee; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pesan Baru dari Portofolio</h1>
        <div class="field">
            <div class="label">Nama</div>
            <div class="value">{{ $name }}</div>
        </div>
        <div class="field">
            <div class="label">Email</div>
            <div class="value">{{ $email }}</div>
        </div>
        <hr>
        <div class="field">
            <div class="label">Pesan</div>
            <div class="value">{{ $pesan }}</div>
        </div>
    </div>
</body>
</html>
