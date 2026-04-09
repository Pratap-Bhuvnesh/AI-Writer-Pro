<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\OrderItem;

class ReviewController extends Controller
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
        $user = auth()->user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        // ✅ VERIFIED PURCHASE CHECK
        $hasPurchased = OrderItem::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id)
            ->where('status', 'delivered'); // important
        })
        ->whereHas('variant.product', function ($q) use ($request) {
            $q->where('id', $request->product_id);
        })
        ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'message' => 'You can only review products you have purchased.'
            ], 403);
        }

        // ✅ Prevent duplicate review
        if (Review::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->exists()) {

            return response()->json([
                'message' => 'You already reviewed this product.'
            ], 400);
        }

        // ✅ Create review
        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json($review, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
