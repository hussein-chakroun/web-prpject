<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
    // Debug: Log all incoming request data
    Log::info('Store method called with data:', [
        'all_data' => $request->all(),
        'files' => $request->allFiles(),
        'method' => $request->method(),
        'content_type' => $request->header('Content-Type')
    ]);

    // More flexible validation with better error messages
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'cost' => 'required|numeric|min:0', // Cost is required as per migration
        'category_id' => 'required|integer|exists:categories,id', // Fixed field name
        'description' => 'required|string|min:10',
        'enabled' => 'boolean', // Made boolean not required as checkboxes may not be sent when unchecked
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed:', [
            'errors' => $validator->errors()->toArray(),
            'input' => $request->all()
        ]);

        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Please fix the validation errors below.');
    }

    try {
        $product = new Product();

        $product->name = $request->input('name');
        $product->quantity = $request->input('quantity');
        $product->price = $request->input('price');
        $product->cost = $request->input('cost'); // Required field, no default needed
        $product->category_id = $request->input('category_id'); // Fixed field name
        $product->description = $request->input('description');
        $product->enabled = $request->has('enabled'); // Handle checkbox correctly

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Validate image file
            if ($image->isValid()) {
                $product->image = base64_encode(file_get_contents($image->getRealPath()));
                Log::info('Image processed successfully', [
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $image->getSize()
                ]);
            } else {
                throw new \Exception('Invalid image file uploaded');
            }
        } else {
            throw new \Exception('No image file found in request');
        }

        $result = $product->save();

        Log::info('Product saved successfully', [
            'result' => $result,
            'product_id' => $product->id,
            'product_data' => $product->toArray()
        ]);

        return redirect()->route('admin.products')->with('success', 'Product added successfully!');

    } catch (\Exception $e) {
        Log::error('Product store error:', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to add product: ' . $e->getMessage());
    }
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'required|string',
            'enabled' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors below.');
        }

        $product = Product::findOrFail($id);

        $product->name = $request->name;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $product->image = base64_encode(file_get_contents($image->getRealPath()));
        }

        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->cost = $request->input('cost');
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->enabled = $request->has('enabled');
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

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }
}
