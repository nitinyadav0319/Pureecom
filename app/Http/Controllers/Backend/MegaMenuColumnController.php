<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MegaMenuColumn;
use App\Models\Category;
use App\Models\Variation;
use App\Models\Brand;

class MegaMenuColumnController extends Controller
{
    // 🔹 List all Mega Menu Columns
    public function index()
    {
        $columns = MegaMenuColumn::with('categories', 'variation')->orderBy('order')->get();
        return view('backend.pages.mega_menu_columns.index', compact('columns'));
    }

    // 🔹 Show form to create new column
    public function create()
    {
        $categories = Category::all(); // All categories with subcategories
        $variations = Variation::where('is_active', 1)->get();    // Active variations
        $brands = Brand::all();                                    // All brands

        return view('backend.pages.mega_menu_columns.create', compact('categories', 'variations', 'brands'));
    }

    // 🔹 Store new column
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:variation,brand,category',
            'variation_id' => 'nullable|exists:variations,id',
            'brand_id' => 'nullable|exists:brands,id',
            'order' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $column = MegaMenuColumn::create($request->only('title', 'type', 'variation_id', 'brand_id', 'order', 'is_active'));

        // Assign categories to column (pivot table)
        if ($request->has('category_ids')) {
            $column->categories()->sync($request->category_ids);
        }

        return redirect()->route('admin.mega_menu_columns.index')->with('success', 'Mega Menu Column created successfully.');
    }

    // 🔹 Show form to edit existing column
    public function edit($id)
    {
        $column = MegaMenuColumn::with('categories')->findOrFail($id);
        $categories = Category::with('childrenCategories')->get();
        $variations = Variation::where('is_active', 1)->get();
        $brands = Brand::all();

        return view('backend.pages.mega_menu_columns.edit', compact('column', 'categories', 'variations', 'brands'));
    }

    // 🔹 Update existing column
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:variation,brand,category',
            'variation_id' => 'nullable|exists:variations,id',
            'brand_id' => 'nullable|exists:brands,id',
            'order' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $column = MegaMenuColumn::findOrFail($id);
        $column->update($request->only('title', 'type', 'variation_id', 'brand_id', 'order', 'is_active'));

        // Update assigned categories
        if ($request->has('category_ids')) {
            $column->categories()->sync($request->category_ids);
        } else {
            $column->categories()->sync([]); // detach all if none selected
        }

        return redirect()->route('admin.mega_menu_columns.index')->with('success', 'Mega Menu Column updated successfully.');
    }

    // 🔹 Delete a column
    public function destroy($id)
    {
        $column = MegaMenuColumn::findOrFail($id);
        $column->categories()->detach(); // Remove pivot relations
        $column->delete();

        return redirect()->route('admin.mega_menu_columns.index')->with('success', 'Mega Menu Column deleted successfully.');
    }
}
