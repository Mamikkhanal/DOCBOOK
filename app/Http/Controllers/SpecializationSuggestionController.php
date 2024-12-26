<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;

class SpecializationSuggestionController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function suggestSpecialization(Request $request)
    {
        $request->validate(['problem' => 'required|string|max:255']);

        $specialization = $this->openAIService->getSpecialization($request->input('problem'));

        return response()->json([
            'specialization' => trim($specialization),
        ]);
    }
}
