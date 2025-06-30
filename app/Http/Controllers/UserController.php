<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    // list user
    public function index()
    {
        $users = User::all();
        return view('admins.user', compact('users'));
    }

    // tạo user admin
    public function create()
    {
        return view('admins.form-add-user');
    }

    // xử lý tạo user
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,admin',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data = $request->only(['username', 'name', 'email', 'phone', 'role']);
        $data['password'] = Hash::make($request->password);

        // lưu ảnh nếu có
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/users', 'public');
        }

        User::create($data);

        return redirect()->route('admin.user.index')->with('success', 'Tạo user thành công!');
    }

    // edit user admin
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admins.form-edit-user', compact('user'));
    }

    // update
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,admin',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = $request->role;
        $user->is_blocked = $request->has('is_blocked');

        // xóa ảnh nếu chọn nút xóa ảnh
        if ($request->has('remove_image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $user->image = null;
        }

        // xóa ảnh cũ thêm ảnh mới
        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $user->image = $request->file('image')->store('uploads/users', 'public');
        }

        $user->save();

        return redirect()->route('admin.user.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    // form sửa thông tin bên shop
    public function editProfile()
    {
        $user = Auth::user();

        return view('shops.profile-edit', compact('user')); // ⬅ bạn nhớ tạo file view tên này
    }

    // xử lý sửa thông tin
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        // xoá ảnh nếu có tick
        if ($request->has('delete_image') && $user->image) {
            Storage::disk('public')->delete($user->image);
            $user->image = null;
        }

        if ($request->hasFile('image')) {
            // xoá ảnh cũ nếu có
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            // lưu ảnh mới
            $path = $request->file('image')->store('uploads/users', 'public');
            $user->image = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return redirect()->route('index')->with('success', 'Cập nhật thông tin thành công!');
    }



}
