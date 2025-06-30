<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // list category admin
    public function index()
    {
        $categories = Category::all();

        return view('admins.category', compact('categories'));
    }

    // form add 
    public function create()
    {
        $categories = Category::all();

        return view('admins.form-add-category', compact('categories'));
    }

    // add
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.category.index')->with('success', 'Thêm danh mục thành công!');
    }

    // form edit
    public function edit($id)
    {
        $categories = Category::findOrFail($id);

        return view('admins.form-edit-category', compact('categories'));
    }

    // update
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
        ];

        if ($request->hasFile('image')) {
            // Xoá ảnh cũ
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $data['image'] = $request->file('image')->store('uploads/categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.category.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    // xóa
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Sản phẩm đã được xóa thành công');
    }

    // xóa nhiều theo checkbox
    public function bulkDelete(Request $request)
    {
        $ids = array_filter(explode(',', $request->ids));

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Không có danh mục nào được chọn.');
        }

        $categories = Category::whereIn('id', $ids)->get();

        foreach ($categories as $category) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
        }

        Category::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Đã xóa danh mục được chọn!');
    }


}
