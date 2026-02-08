<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Boost</title>

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
            width: 400px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #aaa;
        }

        input {
            width: 90%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: none;
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
        <h2>Editar Boost</h2>

        @if (session('success'))
        <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('boosts.update', $boost) }}">
            @csrf
            @method('PUT')

            <label>Nome do Boost</label>
            <input type="text" name="name" value="{{ old('name', $boost->name) }}">
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

            <label>Próximo disponível em</label>
            <input type="datetime-local" name="next_available_at"
                value="{{ old('next_available_at', $boost->next_available_at->format('Y-m-d\TH:i')) }}">
            @error('next_available_at') <div class="error">{{ $message }}</div> @enderror

            <button type="submit">Salvar alterações</button>
            <a href="/dashboard" class="button">Voltar</a>
        </form>
    </div>

</body>

</html>