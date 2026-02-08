<?php

namespace App\Http\Controllers;

use App\Models\Boost;
use Illuminate\Http\Request;

class BoostController extends Controller
{
    /**
     * Lista os boosts do usuário autenticado
     */
    public function index(Request $request)
    {
        // $user = $request->user();

        $boosts = Boost::where('user_id', 0)
            ->get()
            ->map(function ($boost) {
                return [
                    'id' => $boost->id,
                    'name' => $boost->name,
                    'cooldown_minutes' => $boost->cooldown_minutes,
                    'next_available_at' => $boost->next_available_at,
                    'available' => $boost->next_available_at->lte(now()),
                ];
            });

        return response()->json($boosts);
    }

    /**
     * Usa um boost
     */
    public function use($id, Request $request)
    {
        $user = $request->user();

        $boost = Boost::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Verifica se está disponível
        if ($boost->next_available_at->gt(now())) {
            return response()->json([
                'message' => 'Boost is still on cooldown',
                'next_available_at' => $boost->next_available_at,
            ], 422);
        }

        // Usa o boost → reinicia cooldown
        $boost->update([
            'next_available_at' => now()->addMinutes($boost->cooldown_minutes)
        ]);

        return response()->json([
            'message' => 'Boost used successfully',
            'next_available_at' => $boost->next_available_at,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Validação
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'cooldown_minutes' => 'required|integer|min:1',
            'starts_available' => 'sometimes|boolean',
        ]);

        $now = now();
        dd($validated, $now);

        $boost = Boost::create([
            'user_id' => 0,
            'name' => $validated['name'],
            'cooldown_minutes' => $validated['cooldown_minutes'],
            'next_available_at' => ($validated['starts_available'] ?? false)
                ? $now
                : $now->addMinutes($validated['cooldown_minutes']),
        ]);

        return response()->json([
            'message' => 'Boost created successfully',
            'boost' => [
                'id' => $boost->id,
                'name' => $boost->name,
                'cooldown_minutes' => $boost->cooldown_minutes,
                'next_available_at' => $boost->next_available_at,
                'available' => $boost->next_available_at->lte(now()),
            ]
        ], 201);
    }
}
