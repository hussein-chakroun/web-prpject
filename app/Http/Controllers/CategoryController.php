<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Retrieve only enabled categories
    $categories = Category::where('enabled', true)->get();
    
    // Encode images if they exist
    foreach ($categories as $category) {
        if ($category->image) {
            $category->image = base64_encode($category->image);
        }
    }
    
    // Return the view with the filtered categories
    return view('categories', ['categories' => $categories]);
}




    public function adminview()
    {
        $categories = Category::all();
        $totalCategories = $categories->count();
        
        foreach ($categories as $category) {
            if ($category->image) {
                $category->image = base64_encode($category->image);
            }
        }
       
        return view('admin.categories', ['categories' => $categories,'totalCategories'=>$totalCategories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('admin.categories.addcategory');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image',
            'description' => 'required|string',
            'enabled' => 'required|boolean',
        ]);

        $category = new Category;
        $category->name = $request->name;
        if ($request->hasFile('image')) {
            $category->image = file_get_contents($request->file('image')->getRealPath());
        }
        $category->description = $request->description;
        $category->enabled = $request->enabled;
        $category->save();

        return redirect()->route('category')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        if ($category->image) {
            $category->image = base64_encode($category->image);
        }

        return view('categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        
        return view('admin.categories.editcategory', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
            'enabled' => 'required|boolean',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        if ($request->hasFile('image')) {
            $category->image = file_get_contents($request->file('image')->getRealPath());
        }
        $category->description = $request->description;
        $category->enabled = $request->enabled;
        $category->save();

        return redirect()->route('category')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully.');
    }

    public function checkProducts($id)
{
    $hasProducts = Product::where('category_id', $id)->exists();
    return response()->json(['hasProducts' => $hasProducts]);
}

}
