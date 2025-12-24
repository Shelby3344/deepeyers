<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    /**
     * Lista todos os checklists do usuário
     */
    public function index(Request $request): JsonResponse
    {
        $checklists = Checklist::where('user_id', $request->user()->id)
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'target_domain' => $c->target_domain,
                'type' => $c->type,
                'progress' => $c->getProgress(),
                'is_public' => $c->is_public,
                'share_url' => $c->share_token ? url("/checklist/share/{$c->share_token}") : null,
                'session_id' => $c->session_id,
                'created_at' => $c->created_at,
                'updated_at' => $c->updated_at,
            ]);

        return response()->json(['data' => $checklists]);
    }

    /**
     * Cria um novo checklist
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'target_domain' => 'nullable|string|max:255',
            'type' => 'sometimes|string|in:web,api,network,ad,mobile',
            'session_id' => 'nullable|uuid|exists:chat_sessions,id',
        ]);

        $checklist = Checklist::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'target_domain' => $validated['target_domain'] ?? null,
            'type' => $validated['type'] ?? 'web',
            'session_id' => $validated['session_id'] ?? null,
            'states' => [],
            'notes' => [],
        ]);

        return response()->json([
            'message' => 'Checklist criado com sucesso',
            'data' => [
                'id' => $checklist->id,
                'title' => $checklist->title,
                'type' => $checklist->type,
            ],
        ], 201);
    }

    /**
     * Retorna um checklist específico
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $checklist = Checklist::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json([
            'data' => [
                'id' => $checklist->id,
                'title' => $checklist->title,
                'target_domain' => $checklist->target_domain,
                'type' => $checklist->type,
                'states' => $checklist->states ?? [],
                'notes' => $checklist->notes ?? [],
                'progress' => $checklist->getProgress(),
                'is_public' => $checklist->is_public,
                'share_url' => $checklist->share_token ? url("/checklist/share/{$checklist->share_token}") : null,
                'session_id' => $checklist->session_id,
                'created_at' => $checklist->created_at,
                'updated_at' => $checklist->updated_at,
            ],
        ]);
    }

    /**
     * Atualiza um checklist (estados e notas)
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $checklist = Checklist::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'target_domain' => 'nullable|string|max:255',
            'type' => 'sometimes|string|in:web,api,network,ad,mobile',
            'states' => 'sometimes|array',
            'notes' => 'sometimes|array',
            'session_id' => 'nullable|uuid',
        ]);

        $checklist->update($validated);

        return response()->json([
            'message' => 'Checklist atualizado',
            'data' => [
                'id' => $checklist->id,
                'progress' => $checklist->getProgress(),
            ],
        ]);
    }

    /**
     * Deleta um checklist
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $checklist = Checklist::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $checklist->delete();

        return response()->json(['message' => 'Checklist excluído']);
    }

    /**
     * Gera link de compartilhamento
     */
    public function share(Request $request, string $id): JsonResponse
    {
        $checklist = Checklist::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $token = $checklist->generateShareToken();
        $checklist->update(['is_public' => true]);

        return response()->json([
            'message' => 'Link de compartilhamento gerado',
            'data' => [
                'share_url' => url("/checklist/share/{$token}"),
                'token' => $token,
            ],
        ]);
    }

    /**
     * Remove compartilhamento
     */
    public function unshare(Request $request, string $id): JsonResponse
    {
        $checklist = Checklist::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $checklist->update([
            'share_token' => null,
            'is_public' => false,
        ]);

        return response()->json(['message' => 'Compartilhamento removido']);
    }

    /**
     * Visualiza checklist público (sem autenticação)
     */
    public function showPublic(string $token): JsonResponse
    {
        $checklist = Checklist::where('share_token', $token)
            ->where('is_public', true)
            ->firstOrFail();

        return response()->json([
            'data' => [
                'title' => $checklist->title,
                'target_domain' => $checklist->target_domain,
                'type' => $checklist->type,
                'states' => $checklist->states ?? [],
                'notes' => $checklist->notes ?? [],
                'progress' => $checklist->getProgress(),
                'created_at' => $checklist->created_at,
                'updated_at' => $checklist->updated_at,
                // Não expõe user_id nem session_id
            ],
        ]);
    }
}
