<?php

namespace App\Http\Controllers;

use App\Services\MacroCopilotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MacroCopilotController extends Controller
{
    public function __construct(
        protected MacroCopilotService $copilotService
    ) {}

    /**
     * Handle incoming Macro Copilot query.
     */
    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'page' => ['nullable', 'string', 'max:100'],
            'history' => ['nullable', 'array'],
        ]);

        $result = $this->copilotService->ask(
            $validated['message'],
            $validated['page'] ?? null,
            $validated['history'] ?? []
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
