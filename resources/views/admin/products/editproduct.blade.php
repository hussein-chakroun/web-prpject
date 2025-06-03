@extends('admin.layout')

@section('content')
<div class="space-y-6 p-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Edit Product</h1>
        <a href="{{ route('admin.products') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Products
        </a>
    </div>

    <!-- Edit Product Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong class="font-bold">Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.updateproduct', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $product->name) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                </div>

                <!-- Product Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number"
                           name="price"
                           id="price"
                           value="{{ old('price', $product->price) }}"
                           step="0.01"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                </div>

                <!-- Product Cost -->
                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700 mb-1">Cost</label>
                    <input type="number"
                           name="cost"
                           id="cost"
                           value="{{ old('cost', $product->cost) }}"
                           step="0.01"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                </div>

                <!-- Product Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number"
                           name="quantity"
                           id="quantity"
                           value="{{ old('quantity', $product->quantity) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                </div>

                <!-- Product Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id"
                            id="category_id"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Product Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Current Image Preview -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Image</label>
                    @if ($product->image)
                        <img src="data:image/jpeg;base64,{{ $product->image }}"
                             alt="{{ $product->name }}"
                             class="w-32 h-32 object-cover rounded-lg">
                    @else
                        <span class="text-gray-500">No image available</span>
                    @endif
                </div>

                <!-- New Product Image -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Update Product Image</label>
                    <input type="file"
                           name="image"
                           id="image"
                           accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                </div>

                <!-- Product Status -->
                <div class="md:col-span-2">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox"
                               name="enabled"
                               value="1"
                               {{ $product->enabled ? 'checked' : '' }}
                               class="rounded border-gray-300 text-gray-800 focus:ring-gray-500">
                        <span class="text-sm font-medium text-gray-700">Enable Product</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
