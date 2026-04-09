<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use App\Services\AIService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; // ← Import this
use Illuminate\Support\Str;

class AIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Content $content)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $content)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Content $content)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $content)
    {
        //
    }
    public function generate(Request $request, AIService $ai)
    {
       // dd($request->all());
        $user = auth()->user();
        // ❌ No credits
        if ($user->credits <= 5) {
            return response()->json([
                'message' => 'No credits left'
            ], 403);
        }
        /* dd($request->all());
        $request->validate([
            'prompt' => 'required|string',
            'types' => 'required|in:blog,ad,email',
            'tones' => 'required|in:formal,casual,funny',
            'lengths' => 'required|in:short,medium,long',
        ]); */
        // Convert all request values to lowercase
        $input = array_map(function ($value) {
            return is_string($value) ? strtolower($value) : $value;
        }, $request->all());        
        // Validate the input
        $validator = Validator::make($input, [
            'prompt' => 'required|string',
            'types' => 'required|in:blog,ad,email',
            'tones' => 'required|in:formal,casual,funny',
            'lengths' => 'required|in:short,medium,long',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
       
        $type = config('ai.types')[$input['types']];
        $tone = config('ai.tones')[$input['tones']];
        $length = config('ai.lengths')[$input['lengths']];
       
        $prompt = "$type $tone $length on: {$request->prompt}";
        
        $result = $ai->generate($prompt);
        DB::transaction(function () use ($user, $prompt, $result) {
             Content::create([
                'user_id' => auth()->id(),
                'prompt' => $prompt,
                'response' => $result
            ]);
            // ✅ Deduct 1 credit
            $user->decrement('credits');
        });
       
        return response()->json([
            'data' => $result,
            'credits_left' => $user->credits
        ]);
    }
    public function history()
{
    $user = auth()->user();

    $data = \App\Models\Content::where('user_id', $user->id)
                ->latest()
                ->get();

    return response()->json([
        'data' => $data
    ]);
}
}
