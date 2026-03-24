<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        // Đếm số địa điểm đang dùng mỗi danh mục
        $usageCounts = Location::whereNotNull('category')
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('admin.categories.index', compact('categories', 'usageCounts'));
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100|unique:categories,name',
            'color'      => 'required|string|max:20',
            'icon'       => 'required|string|max:10',
            'sort_order' => 'nullable|integer|min:0|max:255',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được thêm thành công!');
    }

    public function show(Category $category)
    {
        return redirect()->route('admin.categories.edit', $category);
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100|unique:categories,name,' . $category->id,
            'color'      => 'required|string|max:20',
            'icon'       => 'required|string|max:10',
            'sort_order' => 'nullable|integer|min:0|max:255',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Cập nhật tên danh mục trong bảng locations nếu tên thay đổi
        if ($category->name !== $validated['name']) {
            Location::where('category', $category->name)
                ->update(['category' => $validated['name']]);
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    public function destroy(Category $category)
    {
        $inUse = Location::where('category', $category->name)->count();

        if ($inUse > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', "Không thể xóa danh mục \"{$category->name}\" vì đang có {$inUse} địa điểm sử dụng.");
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được xóa thành công!');
    }
}
