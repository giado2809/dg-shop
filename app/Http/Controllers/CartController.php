<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // giỏ hàng
    public function index()
    {
        $userId = auth()->id();

        $cartItems = Cart::with(['product', 'color'])
            ->where('user_id', $userId)
            ->get()
            ->map(function ($item)
            {
                $variant = DB::table('product_color_sizes')
                    ->where('product_color_id', $item->color_id)
                    ->where('size', $item->size)
                    ->first();

                $item->variant_quantity = $variant->quantity ?? 0;
                return $item;
            });

        $total = 0;
        foreach ($cartItems as $item)
        {
            $total += $item->product->price * $item->quantity;
        }

        $vouchers = Voucher::where('quantity', '>', 0)->get();

        return view('shops.shopping-cart', compact('cartItems', 'total', 'vouchers'));
    }

    // trang edit cart
    public function edit()
    {
        $userId = auth()->id();
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        return view('shops.cart-edit', compact('cartItems'));
    }

    // ajax add to card
    public function ajaxAddToCart(Request $request)
    {
        $userId = auth()->id();
        $product = Product::findOrFail($request->product_id);

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->where('color_id', $request->color_id)
            ->where('size', $request->size)
            ->first();

        if ($cartItem) 
        {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } 
        else 
        {
            $cartItem = Cart::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'color_id' => $request->color_id,
                'size' => $request->size,
                'quantity' => $request->quantity
            ]);
        }

        if ($request->mode === 'buy')
        {
            return response()->json([
                'message' => 'Đã thêm vào giỏ hàng!',
                'redirect' => route('cart.index'),
                'checked_id' => $cartItem->id
            ]);
        }

        return response()->json([
            'message' => 'Đã thêm vào giỏ hàng!',
            'cart_count' => Cart::where('user_id', $userId)->count(),
        ]);
    }

    // ajax nút tăng
    public function ajaxIncrease($cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $variant = DB::table('product_color_sizes')
            ->where('product_color_id', $cartItem->color_id)
            ->where('size', $cartItem->size)
            ->first();

        if ($variant && $cartItem->quantity < $variant->quantity)
        {
            $cartItem->quantity++;
            $cartItem->save();

            return response()->json([
                'success' => true,
                'quantity' => $cartItem->quantity,
                'total' => $cartItem->quantity * $cartItem->product->price
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Vượt quá tồn kho']);
    }

    // ajax nút giảm
    public function ajaxDecrease($cartId)
    {
        $cartItem = Cart::findOrFail($cartId);

        if ($cartItem->quantity > 1)
        {
            $cartItem->quantity--;
            $cartItem->save();

            return response()->json([
                'success' => true,
                'quantity' => $cartItem->quantity,
                'total' => $cartItem->quantity * $cartItem->product->price
            ]);
        } 
        
        else 
        {
            $cartItem->delete();
            return response()->json(['success' => true, 'deleted' => true]);
        }
    }

    // xóa sp
    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Xoá sản phẩm thành công!');
    }

    // xóa nhiều theo checkbox
    public function deleteSelected(Request $request)
    {
        $ids = explode(',', $request->input('selected_ids'));

        if (!empty($ids))
        {
            Cart::where('user_id', auth()->id())
                ->whereIn('id', $ids)
                ->delete();

            return redirect()->route('cart.index')->with('success', 'Đã xoá các sản phẩm được chọn!');
        }

        return redirect()->route('cart.index')->with('error', 'Chưa chọn sản phẩm nào!');
    }
}
