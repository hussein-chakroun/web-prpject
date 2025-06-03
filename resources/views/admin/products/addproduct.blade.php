@extends('admin.layout')

@section('content')
<div class="space-y-6 p-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Add New Product</h1>
        <a href="{{ route('admin.products') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Products
        </a>
    </div>

    <!-- Add Product Form -->
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

        <form action="{{ route('admin.addproduct.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           required
                           value="{{ old('name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                </div>

                <!-- Product Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number"
                           name="price"
                           id="price"
                           step="0.01"
                           required
                           value="{{ old('price') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                </div>

                <!-- Product Cost -->
                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700 mb-1">Cost</label>
                    <input type="number"
                           name="cost"
                           id="cost"
                           step="0.01"
                           required
                           value="{{ old('cost', 0) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                </div>

                <!-- Product Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number"
                           name="quantity"
                           id="quantity"
                           required
                           value="{{ old('quantity') }}"
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Product Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">{{ old('description') }}</textarea>
                </div>

                <!-- Product Image -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
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
                               {{ old('enabled', '1') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-gray-800 focus:ring-gray-500">
                        <span class="text-sm font-medium text-gray-700">Enable Product</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Add Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
