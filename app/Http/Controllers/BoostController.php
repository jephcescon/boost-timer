<?php

namespace App\Http\Controllers;

use App\Models\Boost;
use Illuminate\Http\Request;

class BoostController extends Controller
{
    public function create()
    {
        return view('boosts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'cooldown' => 'required|regex:/^\d+:\d{2}:\d{2}$/',
            'starts_available' => 'nullable|boolean',
        ]);

        $cooldownMinutes = $this->cooldownToMinutes($validated['cooldown']);

        Boost::create([
            'user_id' => 0, // auth()->id()
            'name' => $validated['name'],
            'cooldown_minutes' => $cooldownMinutes,
            'next_available_at' => $request->boolean('starts_available')
                ? now()
                : now()->addMinutes($cooldownMinutes),
        ]);

        return redirect()
            ->route('boosts.create')
            ->with('success', 'Boost criado com sucesso!');
    }

    public function edit(Boost $boost)
    {
        // abort_if($boost->user_id !== auth()->id(), 403);

        return view('boosts.edit', [
            'boost' => $boost,
            'cooldownFormatted' => $this->minutesToCooldown($boost->cooldown_minutes),
        ]);
    }

    public function update(Request $request, Boost $boost)
    {
        // abort_if($boost->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'cooldown' => 'required|regex:/^\d+:\d{2}:\d{2}$/',
            'next_available_at' => 'required|date',
        ]);

        $cooldownMinutes = $this->cooldownToMinutes($validated['cooldown']);

        $boost->update([
            'name' => $validated['name'],
            'cooldown_minutes' => $cooldownMinutes,
            'next_available_at' => $validated['next_available_at'],
        ]);

        return redirect()
            ->route('boosts.edit', $boost)
            ->with('success', 'Boost atualizado com sucesso!');
    }

    public function reset(Boost $boost)
    {
        // abort_if($boost->user_id !== auth()->id(), 403);

        $boost->update([
            'next_available_at' => now()->addMinutes($boost->cooldown_minutes),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Boost resetado com sucesso!');
    }

    /* ===========================
        Helpers de conversão
       =========================== */

    private function cooldownToMinutes(string $cooldown): int
    {
        [$days, $hours, $minutes] = array_map('intval', explode(':', $cooldown));

        return ($days * 1440) + ($hours * 60) + $minutes;
    }

    private function minutesToCooldown(int $minutes): string
    {
        $days = intdiv($minutes, 1440);
        $minutes %= 1440;

        $hours = intdiv($minutes, 60);
        $minutes %= 60;

        return sprintf('%d:%02d:%02d', $days, $hours, $minutes);
    }
}
