@extends('admin.layout')
@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center">
        <h3 class="text-2xl font-bold text-gray-800">Categories</h3>
        <a href="{{ url('/admin/categories/addcategory') }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add Category
        </a>
    </div>

    <!-- Search Bar and Total Categories -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <span class="text-gray-600">Total Categories: {{ $totalCategories }}</span>
            <div class="flex gap-2 w-full md:w-96">
                <input type="text" 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                       placeholder="Search categories by name..." 
                       id="searchCategories"
                       onkeyup="searchCategories()">
                <button type="button" 
                        onclick="searchCategories()"
                        class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoryTable" class="bg-white divide-y divide-gray-200">
                    @foreach ($categories as $index => $item)
                    <tr id="category-row-{{ $item->id }}" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($item->image)
                                <img src="data:image/jpeg;base64,{{ $item->image }}" 
                                     alt="{{ $item->name }}" 
                                     class="w-24 h-24 object-cover rounded-lg">
                            @else
                                <span class="text-gray-500">No image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $item->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $item->enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->enabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.categories.editcategory', $item->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit
                            </a>
                            <button type="button" 
                                    onclick="showDeleteConfirm({{ $item->id }}, '{{ $item->name }}')"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Delete
                            </button>
                            <form method="POST" action="{{ route('categories.destroy', $item->id) }}" id="delete-form-{{ $item->id }}" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Confirm Delete</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    Please type the category name (<strong id="categoryNameToConfirm"></strong>) to confirm deletion:
                </p>
                <input type="text" 
                       id="confirmCategoryName" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                <input type="hidden" id="deleteCategoryId">
            </div>
            <div class="flex justify-end gap-4 mt-4">
                <button type="button" 
                        onclick="closeModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="button" 
                        onclick="confirmDelete()"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function searchCategories() {
        const input = document.getElementById('searchCategories');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('categoryTable');
        const rows = table.getElementsByTagName('tr');

        Array.from(rows).forEach(function(row) {
            const td = row.getElementsByTagName('td')[2];
            if (td) {
                const txtValue = td.textContent || td.innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }

    function showDeleteConfirm(categoryId, categoryName) {
        fetch(`{{ route('admin.categories.checkProducts', '') }}/${categoryId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.hasProducts) {
                alert(`Cannot delete category with associated products.`);
            } else {
                document.getElementById('confirmCategoryName').value = '';
                document.getElementById('categoryNameToConfirm').innerText = categoryName;
                document.getElementById('deleteCategoryId').value = categoryId;
                document.getElementById('deleteConfirmModal').classList.remove('hidden');
            }
        });
    }

    function closeModal() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
    }

    function confirmDelete() {
        const categoryId = document.getElementById('deleteCategoryId').value;
        const inputName = document.getElementById('confirmCategoryName').value;
        const actualName = document.getElementById('categoryNameToConfirm').innerText;

        if (inputName === actualName) {
            document.getElementById(`delete-form-${categoryId}`).submit();
        } else {
            alert('Category name does not match.');
        }
    }
</script>
@endsection
