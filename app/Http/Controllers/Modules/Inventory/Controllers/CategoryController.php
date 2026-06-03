<?php

namespace App\Http\Controllers\Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('tenant_id', auth()->user()->tenant_id)
            ->withCount('products')
            ->latest()
            ->get();

        return view('modules.inventory.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::where('tenant_id', auth()->user()->tenant_id)
            ->whereNull('parent_id')
            ->get();

        return view('modules.inventory.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'parent_id'   => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:active,inactive'],
        ]);

        Category::create([
            'tenant_id'   => auth()->user()->tenant_id,
            'parent_id'   => $request->parent_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name) . '-' . Str::random(4),
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('tenant_id', auth()->user()->tenant_id)
            ->whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('modules.inventory.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'parent_id'   => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:active,inactive'],
        ]);

        $category->update([
            'parent_id'   => $request->parent_id,
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category with products assigned to it.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}