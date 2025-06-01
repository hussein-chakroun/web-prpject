<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Product::where('enabled', true)   // Include only enabled products
                    ->whereHas('category', function ($q) {
                        $q->where('enabled', true);   // Include only products in enabled categories
                    });

    // Handle search
    if ($request->has('search') && !empty($request->search)) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Handle category filter
    if ($request->has('category') && !empty($request->category)) {
        $query->where('category_id', $request->category);
    }

    // Handle price filter
    if ($request->has('min_price') || $request->has('max_price')) {
        $query->whereBetween('price', [
            $request->input('min_price', 0),
            $request->input('max_price', PHP_INT_MAX)
        ]);
    }

    // Paginate results
    $products = $query->paginate(9);
    $categories = Category::select('id', 'name')
                          ->where('enabled', true)   // Only include enabled categories
                          ->get();
    $categoryMap = $categories->pluck('name', 'id');

    return view('products', [
        'products' => $products,
        'categoryMap' => $categoryMap,
        'categories' => $categories,
    ]);
}

    
    

    

public function home(Request $request)
{
    $query = Product::where('enabled', true)   // Only include enabled products
                   ->whereHas('category', function($query) {
                       $query->where('enabled', true);   // Only include products in enabled categories
                   });

    // Handle search
    if ($request->has('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Handle category filter
    if ($request->has('category')) {
        $query->where('category_id', $request->category);
    }

    // Paginate results
    $products = $query->paginate(6);
    $categories = Category::select('id', 'name')
                          ->where('enabled', true)   // Only include enabled categories
                          ->get();
    $categoryMap = $categories->pluck('name', 'id');

    return view('home', [
        'products' => $products,
        'categoryMap' => $categoryMap,
        'categories' => $categories,
    ]);
}




public function adminview()
{
    $products = Product::paginate(9);
    $categories = Category::select('id', 'name')->get();
    $categoryMap = $categories->pluck('name', 'id');
     
  

    return view('admin.products', ['products' => $products, 'categoryMap' => $categoryMap, 'totalProducts' => Product::count()]);
}







    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('id','name')->get();
        

        return view('admin.products.addproduct',['categories'=>$categories]);
    }

   







 /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',
            'category' => 'required|integer|exists:categories,id',
            'description' => 'required|string',
            'enabled' => 'required|boolean',
        ]);
    
        $product = new Product();
    
        $product->name = $request->name;
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $product->image = base64_encode(file_get_contents($image->getRealPath()));
        }
    
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->cost = $request->cost;
        $product->category_id = $request->category;
        $product->description = $request->description;
        $product->enabled = $request->enabled;
        $product->save();
    
        return redirect()->route('admin.products')->with('success', 'Product added successfully!');
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $singleproduct= Product::findOrFail($id);
        $products=Product::all();
        $category = Category::select('id', 'name')->get();
    $categoryMap = $category->pluck('name', 'id');
        
       
    
        return view('productdetails',['products'=>$products,'product'=>$singleproduct,'category'=>$category,'categoryMap' => $categoryMap,]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product=Product::findOrFail($id);
        $categories=Category::all();
      

        return view('admin.products.editproduct',['product'=>$product,'categories'=>$categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',
            'category' => 'required|integer|exists:categories,id',
            'description' => 'required|string',
            'enabled' => 'required|boolean',
        ]);
    
        $product = Product::findOrFail($id);
        
        $product->name = $request->name;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $product->image = base64_encode(file_get_contents($image->getRealPath()));
        } else {
            $product-> image = $product->image; // Keep the existing image
        }
    
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->cost = $request->cost;
        $product->category_id = $request->category;
        $product->description = $request->description;
        $product->enabled = $request->enabled;
        $product->save();
    
        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product= Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Category deleted successfully.');
    }
}
