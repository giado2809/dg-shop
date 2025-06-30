<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    // form đánh giá
    public function create($id)
    {
        $order = Order::with('items.product', 'items.review')->where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        return view('shops.form-review', compact('order'));
    }

    // xử lý đánh giá
    public function store(Request $request, $id)
    {
        $order = Order::with('items')->where('id', $id)
                ->where('user_id', Auth::id())
                ->where('status', 'completed')
                ->firstOrFail();

        $request->validate([
            'reviews.*.order_item_id' => 'required|exists:order_items,id',
            'reviews.*.rating' => 'required|integer|min:1|max:5',
            'reviews.*.content' => 'required|string',
            'reviews.*.image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->input('reviews') as $index => $reviewData) {
                $item = $order->items->firstWhere('id', $reviewData['order_item_id']);

                if (!$item) continue;
                if ($item->review) continue;

                $review = new Review([
                    'user_id' => Auth::id(),
                    'order_item_id' => $item->id,
                    'rating' => $reviewData['rating'],
                    'content' => $reviewData['content'],
                ]);

                // lấy ảnh từ request->file
                $imageFile = $request->file("reviews.$index.image");

                if ($imageFile) {
                    $imagePath = $imageFile->store('reviews', 'public');
                    $review->image = $imagePath;
                }

                $review->save();
            }

            DB::commit();
            return redirect()->route('order.index')->with('success', 'Đánh giá đã được gửi!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Có lỗi xảy ra, thử lại sau!');
        }
    }

    // xem đánh giá
    public function show($id)
    {
        $order = Order::with(['items.product', 'items.review.user'])
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->where('status', 'completed')
                ->firstOrFail();

        $reviews = $order->reviews;

        if (!$reviews) {
            return redirect()->route('order.index')->with('error', 'Đơn hàng này chưa được đánh giá.');
        }

        return view('shops.review-detail', compact('order', 'reviews'));
    }

    // list đánh giá admin
    public function index(Request $request)
    {
        $query = Review::with(['user', 'orderItem.product'])->latest();

        // lọc theo số sao
        if ($request->has('rating') && $request->rating !== '') {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->get();

        return view('admins.review', compact('reviews'));
    }

    // xóa đánh giá
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // nếu có ảnh thì xóa ảnh trong storage
        if ($review->image && Storage::disk('public')->exists($review->image)) {
            Storage::disk('public')->delete($review->image);
        }

        $review->delete();

        return back()->with('success', 'Đã xóa bình luận thành công!');
    }
}
