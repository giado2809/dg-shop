<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductColorSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // list product admin
    public function index(Request $request)
    {
        $query = Product::with(['category', 'colors.sizes']);

        // lọc theo category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        $products = $query->get();

        // tổng số lượng trong kho
        $products->map(function ($product) {
            $product->total_quantity = $product->colors->flatMap->sizes->sum('quantity');
            return $product;
        });

        $categories = Category::all();

        return view('admins.product', compact('products', 'categories'));
    }

    // form add product
    public function create()
    {
        $categories = Category::all();

        return view('admins.form-add-product', compact('categories'));
    }

    // add
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric|min:0|lte:price', // giá sale < giá gốc
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'tag' => 'nullable|string|in:hot-sales,new-arrivals',
        ]);
        $validated['sale_price'] = $request->input('sale_price'); // có thể bỏ trống nên request riêng

        // upload ảnh
        $path = $request->file('image')->store('uploads/products', 'public');
        $validated['image'] = $path;

        $product = Product::create($validated);

        // xử lý các màu
        if ($request->has('colors')) {
            foreach ($request->colors as $colorData) {
                if (!isset($colorData['color'])) continue;

                // upload ảnh theo màu
                $colorImage = null;
                if (isset($colorData['image'])) {
                    $colorImage = $colorData['image']->store('uploads/colors', 'public');
                }

                $productColor = ProductColor::create([
                    'product_id' => $product->id,
                    'color' => $colorData['color'],
                    'image' => $colorImage,
                ]);

                // size và số lượng
                if (isset($colorData['sizes'])) {
                    foreach ($colorData['sizes'] as $sizeData) {
                        if (!isset($sizeData['size']) || !isset($sizeData['quantity'])) continue;

                        ProductColorSize::create([
                            'product_color_id' => $productColor->id,
                            'size' => $sizeData['size'],
                            'quantity' => $sizeData['quantity'],
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Đã thêm sản phẩm thành công!');
    }

    // detail sp
    public function show($id)
    {
        $product = Product::with(['category', 'colors.sizes'])->findOrFail($id);
        $product->total_quantity = $product->colors->flatMap->sizes->sum('quantity');

        return view('admins.product-detail', compact('product'));
    }

    // form edit
    public function edit($id)
    {
        $product = Product::with(['colors.sizes'])->findOrFail($id);
        $categories = Category::all();

        return view('admins.form-edit-product', compact('product', 'categories'));
    }

    // update
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
            'tag' => 'nullable|string|in:hot-sales,new-arrivals',
        ]);
        $validated['sale_price'] = $request->input('sale_price'); // có thể bỏ trống nên request riêng

        // có ảnh mới -> xoá ảnh cũ
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('uploads/products', 'public');
        }

        $product->update($validated);

        // xử lý màu và size
        if ($request->has('colors')) {
            foreach ($request->colors as $colorData) {
                if (!isset($colorData['color'])) continue;

                $productColor = null;

                // nếu là màu cũ -> update
                if (isset($colorData['id'])) {
                    $productColor = ProductColor::find($colorData['id']);
                    if (!$productColor) continue;

                    $productColor->color = $colorData['color'];

                    if (isset($colorData['image'])) {
                        if ($productColor->image && Storage::disk('public')->exists($productColor->image)) {
                            Storage::disk('public')->delete($productColor->image);
                        }

                        $productColor->image = $colorData['image']->store('uploads/colors', 'public');
                    }

                    $productColor->save();
                } else {
                    // màu mới
                    $productColor = ProductColor::create([
                        'product_id' => $product->id,
                        'color' => $colorData['color'],
                        'image' => isset($colorData['image']) ? $colorData['image']->store('uploads/colors', 'public') : null,
                    ]);
                }

                // xử lý size
                if (isset($colorData['sizes'])) {
                    foreach ($colorData['sizes'] as $sizeData) {
                        if (!isset($sizeData['size']) || !isset($sizeData['quantity'])) continue;

                        if (isset($sizeData['id'])) {
                            // sửa size cũ
                            $size = ProductColorSize::find($sizeData['id']);
                            if ($size) {
                                $size->update([
                                    'size' => $sizeData['size'],
                                    'quantity' => $sizeData['quantity'],
                                ]);
                            }
                        } else {
                            // tạo size mới
                            ProductColorSize::create([
                                'product_color_id' => $productColor->id,
                                'size' => $sizeData['size'],
                                'quantity' => $sizeData['quantity'],
                            ]);
                        }
                    }
                }
            }
        }

        // xoá các size được đánh dấu xoá
        if ($request->has('deleted_sizes')) {
            foreach ($request->deleted_sizes as $sizeId) {
                $size = ProductColorSize::find($sizeId);
                if ($size) $size->delete();
            }
        }

        // xoá các màu được đánh dấu xoá (kéo theo size con)
        if ($request->has('deleted_colors')) {
            foreach ($request->deleted_colors as $colorId) {
                $color = ProductColor::with('sizes')->find($colorId);
                if ($color) {
                    $color->sizes()->delete();
                    $color->delete();
                }
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Cập nhật sản phẩm thành công');
    }

    // delete
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Xóa file ảnh trong storage nếu tồn tại
        if (!empty($product->image) && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Sản phẩm đã được xóa thành công');
    }

    // xóa nhiều theo checkbox
    public function bulkDelete(Request $request)
    {
        $ids = array_filter(explode(',', $request->ids));

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Không có sản phẩm nào được chọn.');
        }

        $products = Product::whereIn('id', $ids)->get();

        foreach ($products as $product) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // xóa ảnh của từng màu
            foreach ($product->colors as $color) {
                if ($color->image && Storage::disk('public')->exists($color->image)) {
                    Storage::disk('public')->delete($color->image);
                }
            }
        }

        Product::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Đã xóa sản phẩm được chọn!');
    }
}
