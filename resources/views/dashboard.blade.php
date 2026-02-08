<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Boost Timer</title>

    <style>
        body {
            background: #0f1220;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        /* ===== Header ===== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .header a {
            color: #ff7a18;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        .header a:hover {
            text-decoration: underline;
        }

        /* ===== Boost grid ===== */
        .boost-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }

        /* ===== Boost card ===== */
        .boost-box {
            background: #1b1f3b;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .3);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .boost-name {
            font-size: 14px;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .boost-timer {
            font-size: 30px;
            font-weight: bold;
            color: #ff7a18;
            font-family: monospace;
        }

        .boost-actions {
            display: flex;
            gap: 10px;
        }

        button {
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-reset {
            background: #ffb703;
            color: #000;
        }

        .btn-reset:hover {
            background: #ffc933;
        }

        .btn-edit {
            background: #3a86ff;
            color: #fff;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-edit:hover {
            background: #5a9bff;
        }

        /* ===== Server time ===== */
        .server-time {
            font-size: 14px;
            color: #ccc;
        }
    </style>
</head>

<body>

    <!-- ===== Header ===== -->
    <div class="header">
        <div class="server-time">
            Hora do servidor:
            <strong id="server-time"></strong>
        </div>

        <div>
            <a href="{{ route('boosts.create') }}">➕ Criar Boost</a>
        </div>
    </div>

    <!-- ===== Boost list ===== -->
    <div class="boost-grid">
        @foreach ($boosts as $boost)
        <div class="boost-box">
            <span class="boost-name">{{ $boost->name }}</span>

            <span class="boost-timer"
                data-next-boost="{{ $boost->next_available_at->timestamp * 1000 }}">
                00:00:00
            </span>

            <div class="boost-actions">
                <form method="POST" action="{{ route('boosts.reset', $boost->id) }}">
                    @csrf
                    <button type="submit" class="btn-reset">🔄 Resetar</button>
                </form>

                <a href="{{ route('boosts.edit', $boost->id) }}" class="btn-edit">
                    ✏️ Editar
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ===== Server time JS ===== -->
    <script>
        const serverTimeEl = document.getElementById('server-time');
        let serverNow = {{$serverTimestamp }};

        function updateServerTime() {
            const date = new Date(serverNow);
            serverTimeEl.textContent =
                date.toLocaleDateString('pt-BR') + ' ' +
                date.toLocaleTimeString('pt-BR');
            serverNow += 1000;
        }

        updateServerTime();
        setInterval(updateServerTime, 1000);
    </script>

    <!-- ===== Boost timers JS ===== -->
    <script>
        document.querySelectorAll('.boost-timer').forEach(timer => {
            const nextBoostAt = Number(timer.dataset.nextBoost);

            function update() {
                const now = Date.now();
                let diff = Math.max(0, nextBoostAt - now);

                const h = Math.floor(diff / 3600000);
                diff %= 3600000;

                const m = Math.floor(diff / 60000);
                diff %= 60000;

                const s = Math.floor(diff / 1000);

                timer.textContent =
                    String(h).padStart(2, '0') + ':' +
                    String(m).padStart(2, '0') + ':' +
                    String(s).padStart(2, '0');
            }

            update();
            setInterval(update, 1000);
        });
    </script>

</body>

</html>