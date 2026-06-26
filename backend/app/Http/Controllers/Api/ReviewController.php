<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Product $product)
    {
        $reviews = $product->reviews()->with('user:id,name')->latest()->get();
        return response()->json([
            'reviews'   => $reviews,
            'avg'       => round($reviews->avg('rating'), 1),
            'count'     => $reviews->count(),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $product->id],
            ['rating'  => $request->rating, 'comment' => $request->comment]
        );

        return response()->json(['message' => 'Review saved', 'review' => $review->load('user:id,name')], 201);
    }

    public function destroy(Request $request, Review $review)
    {
        abort_if($review->user_id !== $request->user()->id, 403);
        $review->delete();
        return response()->json(['message' => 'Review deleted']);
    }
}
