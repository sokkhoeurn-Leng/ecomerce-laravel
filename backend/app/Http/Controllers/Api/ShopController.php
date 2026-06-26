<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function categories()
    {
        return response()->json(
            Category::where('status', true)->withCount('products')->orderBy('name')->get()
        );
    }

    public function products(Request $request)
    {
        $query = Product::with('category')->where('status', true);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->featured) {
            $query->where('featured', true);
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->in_stock) {
            $query->where('stock_quantity', '>', 0);
        }

        if ($request->min_rating) {
            $query->whereHas('reviews', function ($q) use ($request) {
                $q->select(DB::raw('AVG(rating) as avg_rating'))
                  ->havingRaw('AVG(rating) >= ?', [$request->min_rating]);
            });
        }

        $sortMap = [
            'price_asc'  => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
            'newest'     => ['created_at', 'desc'],
            'oldest'     => ['created_at', 'asc'],
        ];

        if ($request->sort === 'popular') {
            $query->withCount('orderItems')->orderByDesc('order_items_count');
        } else {
            [$col, $dir] = $sortMap[$request->sort] ?? ['created_at', 'desc'];
            $query->orderBy($col, $dir);
        }

        return response()->json($query->paginate($request->per_page ?? 12));
    }

    public function product(Product $product)
    {
        if (!$product->status) abort(404);

        $product->load('category');
        $product->load(['reviews' => fn($q) => $q->with('user:id,name')->latest()]);
        $product->avg_rating = round($product->reviews->avg('rating'), 1);
        $product->review_count = $product->reviews->count();

        return response()->json($product);
    }
}
