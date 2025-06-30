<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // checkout theo checkbox sp
    public function checkoutSelected(Request $request)
    {
        $selectedString = $request->input('selected_ids', ''); // "1,2,3"
        $selectedIds = array_filter(explode(',', $selectedString)); // [1,2,3]

        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Bạn chưa chọn sản phẩm nào để mua!');
        }

        $cart = Cart::with('product')
            ->whereIn('id', $selectedIds)
            ->get();

        $total = $cart->sum(fn($item) => $item->product->price * $item->quantity);

        return view('shops.checkout', compact('cart', 'total'));
    }

    // xử lý thanh toán
    public function placeOrder(Request $request)
    {
        $request->validate([
            'name'    => 'required|string',
            'email'   => 'required|string',
            'phone'   => 'required|string',
            'address' => 'required|string',
        ]);

        $selectedIds = $request->input('selected_cart_ids', []);

        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm nào để đặt hàng!');
        }

        $cartItems = Cart::with(['product', 'color'])->whereIn('id', $selectedIds)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Các sản phẩm được chọn không còn trong giỏ hàng!');
        }

        DB::beginTransaction();

        try {
            $total_price = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

            // Xử lý mã giảm giá
            $voucher = null;
            $discount_amount = 0;

            if ($request->filled('voucher_code')) {
                $voucher = Voucher::where('code', $request->voucher_code)
                    ->where('quantity', '>', 0)
                    ->first();

                if (!$voucher) {
                    return redirect()->back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết.');
                }

                if ($total_price < $voucher->min_total) {
                    return redirect()->back()->with('error', 'Đơn hàng chưa đủ điều kiện áp dụng mã giảm giá.');
                }

                $discount_amount = $total_price * $voucher->discount_percent / 100;
                $total_price -= $discount_amount;

                // trừ số lượng mã sau khi dùng
                $voucher->decrement('quantity');
            }

            $order = Order::create([
                'user_id'     => Auth::id(),
                'name'        => $request->name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'address'     => $request->address,
                'total_price' => $total_price,
                'voucher_code'   => $voucher?->code,
                'discount_amount'=> $discount_amount,
                'status'      => 'pending',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'color_id'   => $item->color_id,
                    'size'       => $item->size,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);

                // trừ số lượng tồn kho sau khi mua
                DB::table('product_color_sizes')
                    ->where('product_color_id', $item->color_id)
                    ->where('size', $item->size)
                    ->decrement('quantity', $item->quantity);
                }

            Cart::whereIn('id', $selectedIds)->delete();

            DB::commit();

            return redirect()->route('order.index', $order->id)->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại sau!');
        }
    }

    // checkout 1 sp
    public function checkoutOne($cartId)
    {
        $cartItem = Cart::with(['product', 'color'])
                    ->where('id', $cartId)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        $total = $cartItem->product->price * $cartItem->quantity;

        return view('shops.checkout-one', compact('cartItem', 'total'));
    }

    // xử lý mua 1 sp
    public function placeOrderOne(Request $request, $cartId)
    {
        $request->validate([
            'name'    => 'required|string',
            'phone'   => 'required|string',
            'address' => 'required|string',
        ]);

        $cartItem = Cart::with('product')->where('id', $cartId)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        DB::beginTransaction();
        try {
            $total_price = $cartItem->product->price * $cartItem->quantity;

            // Xử lý mã giảm giá
            $voucher = null;
            $discount_amount = 0;
            $voucher_code = $request->input('voucher_code');

            if (!empty($voucher_code)) {
                $voucher = Voucher::where('code', $voucher_code)
                    ->where('quantity', '>', 0)
                    ->first();

                if (!$voucher) {
                    return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết.');
                }

                if ($total_price < $voucher->min_total) {
                    return back()->with('error', 'Đơn hàng chưa đủ điều kiện áp dụng mã.');
                }

                $discount_amount = $total_price * $voucher->discount_percent / 100;
                $total_price -= $discount_amount;

                $voucher->decrement('quantity');
            }

            $order = Order::create([
                'user_id'        => Auth::id(),
                'name'           => $request->name,
                'email'          => $request->email,
                'phone'          => $request->phone,
                'address'        => $request->address,
                'total_price'    => $total_price,
                'discount_amount'=> $discount_amount,
                'voucher_code'   => $voucher_code,
                'status'         => 'pending',
            ]);

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity'   => $cartItem->quantity,
                'price'      => $cartItem->product->price,
                'color_id'   => $cartItem->color_id,
                'size'       => $cartItem->size,
            ]);

            DB::table('product_color_sizes')
                ->where('product_color_id', $cartItem->color_id)
                ->where('size', $cartItem->size)
                ->decrement('quantity', $cartItem->quantity);

            $cartItem->delete();

            DB::commit();
            return redirect()->route('order.index')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Có lỗi xảy ra!');
        }
    }

    // trang list đơn hàng
    public function listOrder(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::with(['items.product', 'items.color', 'reviews'])
                ->where('user_id', Auth::id())
                ->when($status, fn($q) => $q->where('status', $status))
                ->orderByDesc('created_at')
                ->get();

        return view('shops.order', compact('orders', 'status'));
    }

    // detail đơn hàng
    public function showOrder($id)
    {
        $order = Order::with(['items.product', 'items.color'])
                ->where('user_id', Auth::id())->findOrFail($id);

        return view('shops.order-detail', compact('order'));
    }

    // hủy mua
    public function cancel($id)
    {
        $order = Order::with('items')->where('user_id', Auth::id())->findOrFail($id);

        // cho huỷ nếu trạng thái là pending
        if ($order->status !== 'pending') {
            return back()->with('error', 'Đơn hàng không thể huỷ!');
        }

        DB::beginTransaction();
        try {
            // hoàn hàng lại kho
            foreach ($order->items as $item) {
                DB::table('product_color_sizes')
                    ->where('product_color_id', $item->color_id)
                    ->where('size', $item->size)
                    ->increment('quantity', $item->quantity);
            }

            // cập nhật trạng thái hủy
            $order->status = 'cancelled';
            $order->save();

            DB::commit();
            return back()->with('success', 'Đã huỷ đơn hàng và hoàn kho thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi khi huỷ đơn hàng!');
        }
    }

    // list đơn hàng admin
    public function index(Request $request)
    {
        $status = $request->input('status');

        $query = Order::with('user')->orderByDesc('created_at');

        // lọc theo trạng thái
        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->get();

        return view('admins.don-hang', compact('orders', 'status'));
    }

    // chi tiết order admin
    public function show($id)
    {
        $order = Order::with(['items.product', 'items.color'])->findOrFail($id);
        
        return view('admins.chi-tiet-don-hang', compact('order'));
    }

    // đổi trạng thái đơn hàng admin
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::with('items')->findOrFail($id);

        DB::beginTransaction();
        try {
            // đổi trạng thái cancelled thì hoàn hàng
            if ($order->status !== 'cancelled' && $request->status === 'cancelled') {
                foreach ($order->items as $item) {
                    DB::table('product_color_sizes')
                        ->where('product_color_id', $item->color_id)
                        ->where('size', $item->size)
                        ->increment('quantity', $item->quantity);
                }
            }

            $order->status = $request->status;
            $order->save();

            DB::commit();
            return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi khi cập nhật trạng thái!');
        }
    }

}
