<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Criar Boost</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f1220;
            color: #fff;
            padding: 40px;
        }

        .card {
            background: #1b1f3b;
            padding: 20px;
            border-radius: 12px;
            width: 360px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #aaa;
        }

        input[type="text"],
        input[type="number"] {
            width: 90%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: none;
        }

        .checkbox {
            margin-top: 15px;
        }

        button,
        .button {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 12px;
            width: 100%;
            background: #ff7a18;
            color: #000;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .success {
            color: #4ade80;
            margin-bottom: 10px;
        }

        .error {
            color: #f87171;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Criar Boost</h2>

        @if (session('success'))
        <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('boosts.store') }}">
            @csrf

            <label>Nome do Boost</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <div class="error">{{ $message }}</div> @enderror

            <label for="cooldown">Cooldown (Dias:HH:MM)</label>
            <input
                type="text"
                name="cooldown"
                id="cooldown"
                placeholder="00:00:00"
                value="{{ old('cooldown', $cooldownFormatted ?? '') }}"
                required />
            @error('cooldown_minutes') <div class="error">{{ $message }}</div> @enderror

            <div class="checkbox">
                <label>
                    <input type="checkbox" name="starts_available" value="1"
                        {{ old('starts_available') ? 'checked' : '' }}>
                    Disponível imediatamente
                </label>
            </div>

            <button type="submit">Criar Boost</button>
            <a href="/dashboard" class="button">Voltar ao Dashboard</a>
        </form>
    </div>

</body>

</html>