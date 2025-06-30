<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // list voucher
    public function index()
    {
        $vouchers = Voucher::latest()->get();

        return view('admins.voucher', compact('vouchers'));
    }

    // form tạo voucher
    public function create()
    {
        return view('admins.form-add-voucher');
    }

    // xử lý tạo voucher
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers,code',
            'discount_percent' => 'required|integer|min:1|max:100',
            'quantity' => 'required|integer|min:1',
            'min_total' => 'required|integer|min:0',
        ]);

        Voucher::create($request->only(['code', 'discount_percent', 'quantity', 'min_total']));

        return redirect()->route('admin.voucher.index')->with('success', 'Tạo mã giảm giá thành công!');
    }

    // form sửa voucher
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);

        return view('admins.form-edit-voucher', compact('voucher'));
    }

    // xử lý sửa
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'quantity' => 'required|integer|min:0',
            'min_total' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($validated);

        return redirect()->route('admin.voucher.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    // xóa 
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.voucher.index')->with('success', 'Xoá mã giảm giá thành công!');
    }

    // xóa nhiều theo checkbox
    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        Voucher::whereIn('id', $ids)->delete();

        return redirect()->route('admin.voucher.index')->with('success', 'Xoá thành công các mã đã chọn!');
    }

    // xử lý áp dụng mã
    public function check(Request $request)
    {
        $code = $request->input('code');
        $total = $request->input('total');

        $voucher = Voucher::where('code', $code)->where('quantity', '>', 0)->first();

        if (!$voucher) {
            return response()->json(['error' => 'Mã không hợp lệ hoặc đã hết'], 404);
        }

        if ($total < $voucher->min_total) {
            return response()->json(['error' => 'Chưa đủ điều kiện áp dụng mã'], 400);
        }

        $discount = round($total * $voucher->discount_percent / 100);

        return response()->json([
            'success' => true,
            'code' => $voucher->code,
            'percent' => $voucher->discount_percent,
            'discount' => $discount,
            'final_total' => $total - $discount
        ]);
    }


}
