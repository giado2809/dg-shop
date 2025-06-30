<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // trang home
    public function index()
    {
        $products = Product::with('category')->get();

        foreach ($products as $product) {
            $totalSold = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('order_items.product_id', $product->id)
                ->whereIn('orders.status', ['processing', 'completed']) // chỉ tính đơn đang giao hoặc đã giao
                ->sum('order_items.quantity');

            // số lượng đã bán
            $product->total_sold = $totalSold;

            // tính sao trung bình
            $product->avg_rating = $product->reviews()->avg('rating');
        }

        $categories = Category::all();

        return view('shops.home', compact('products', 'categories'));
    }

    // trang shop
    public function shop(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category');
        $priceFrom = $request->input('price_from');
        $priceTo = $request->input('price_to');
        $sort = $request->input('sort');

        $products = Product::query();
        $categoryName = null;

        // search
        if ($search) {
            $products->where('name', 'like', "%$search%");
        }

        // lọc theo danh mục
        if ($categoryId) {
            $products->where('category_id', $categoryId);

            $category = Category::find($categoryId);

            if ($category) {
                $categoryName = $category->name;
            }
        }

        $products->select('*')->selectRaw('IFNULL(sale_price, price) as final_price');

        // lọc theo khoảng giá
        if ($priceFrom) {
            $products->having('final_price', '>=', $priceFrom);
        }

        if ($priceTo) {
            $products->having('final_price', '<=', $priceTo);
        }

        // sắp xếp theo giá cao thấp
        if ($sort) {
            if ($sort == 'asc') {
                $products->orderBy('final_price', 'asc');
            } elseif ($sort == 'desc') {
                $products->orderBy('final_price', 'desc');
            }
        }

        $products = $products->get();

        foreach ($products as $product) {
            $totalSold = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('order_items.product_id', $product->id)
                ->whereIn('orders.status', ['processing', 'completed'])
                ->sum('order_items.quantity');

                // số lượng đã bán
            $product->total_sold = $totalSold;
            // tính sao trung bình
            $product->avg_rating = $product->reviews()->avg('rating');
        }

        // đếm số lượng sản phẩm trong từng cat
        $categories = Category::withCount('products')->get();
   
        return view('shops.shop', compact(
            'products',
            'categoryName',
            'search',
            'categories',
            'categoryId',
            'priceFrom',
            'priceTo',
            'sort'
        ));
    }

    // trang chi tiết sp
    public function detail($id)
    {
        $product = Product::with(['colors.sizes'])->findOrFail($id);
        $product->avg_rating = $product->reviews()->avg('rating');
        $productVariants = [];

        foreach ($product->colors as $color) {
            $productVariants[$color->id] = [
                'image' => asset('storage/' . $color->image),
                'sizes' => $color->sizes->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'size' => $s->size,
                        'quantity' => $s->quantity
                    ];
                })->values()->toArray()
            ];
        }

        // sản phẩm cùng loại
        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $id)
                                ->get();

        foreach ($relatedProducts as $item) {
            $sold = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.product_id', $item->id)
                    ->whereIn('orders.status', ['processing', 'completed'])
                    ->sum('order_items.quantity');

            // số lượng đã bán
            $item->total_sold = $sold;
            // tính sao trung bình
            $item->avg_rating = $item->reviews()->avg('rating');
        }

        // số lượng đã bán của sản phẩm detail
        $totalSold = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.product_id', $product->id)
                    ->whereIn('orders.status', ['processing', 'completed'])
                    ->sum('order_items.quantity');

        // tính sao trung bình
        $avgRating = $product->reviews()->avg('rating');

        return view('shops.shop-details', compact('product', 'relatedProducts', 'productVariants', 'totalSold'));
    }

}