@extends('layouts.admin')

@section('title', 'Record Walk-in Sale')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <p class="text-sm font-medium text-indigo-600">Point of sale</p>
        <h1 class="mt-1 text-2xl font-bold text-gray-900">Record walk-in sale</h1>
        <p class="mt-2 text-sm text-gray-500">Select what the customer bought. Stock will be reduced automatically and the sale will appear in Orders and Reports.</p>
    </div>

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 sm:p-7">
        <form method="POST" action="{{ route('admin.sales.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                <select id="product_id" name="product_id" required class="mt-2 block w-full rounded-xl border-gray-300 px-3 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select a product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-sizes='@json($product->sizes ?? [])' data-colors='@json($product->colors ?? [])' data-stock="{{ $product->stock }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} - ₦{{ number_format($product->discounted_price, 2) }} ({{ $product->stock }} in stock)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" required class="mt-2 block w-full rounded-xl border-gray-300 px-3 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="size" class="block text-sm font-medium text-gray-700">Size <span class="font-normal text-gray-400">(optional)</span></label>
                    <select id="size" name="size" class="mt-2 block w-full rounded-xl border-gray-300 px-3 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">No size</option>
                    </select>
                </div>
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700">Color <span class="font-normal text-gray-400">(optional)</span></label>
                    <select id="color" name="color" class="mt-2 block w-full rounded-xl border-gray-300 px-3 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">No color</option>
                    </select>
                </div>
            </div>

            <div id="sale-summary" class="hidden rounded-xl bg-gray-50 border border-gray-200 p-4 text-sm text-gray-700"></div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Record as Bought</button>
            </div>
        </form>
    </div>
</div>

<script>
    const productSelect = document.getElementById('product_id');
    const sizeSelect = document.getElementById('size');
    const colorSelect = document.getElementById('color');
    const quantityInput = document.getElementById('quantity');
    const summary = document.getElementById('sale-summary');
    const oldSize = @json(old('size', ''));
    const oldColor = @json(old('color', ''));

    function updateOptions(select, values, emptyLabel, selectedValue) {
        select.innerHTML = `<option value="">${emptyLabel}</option>`;
        values.forEach(value => {
            const option = new Option(value, value, false, value === selectedValue);
            select.add(option);
        });
        select.disabled = values.length === 0;
    }

    function updateSaleDetails() {
        const option = productSelect.options[productSelect.selectedIndex];
        if (!option || !option.value) {
            updateOptions(sizeSelect, [], 'No size', '');
            updateOptions(colorSelect, [], 'No color', '');
            summary.classList.add('hidden');
            return;
        }

        const sizes = JSON.parse(option.dataset.sizes || '[]');
        const colors = JSON.parse(option.dataset.colors || '[]');
        updateOptions(sizeSelect, sizes, 'No size', oldSize || sizeSelect.value);
        updateOptions(colorSelect, colors, 'No color', oldColor || colorSelect.value);
        summary.textContent = `${option.textContent.trim()} | Quantity: ${quantityInput.value || 1}`;
        summary.classList.remove('hidden');
    }

    productSelect.addEventListener('change', updateSaleDetails);
    quantityInput.addEventListener('input', updateSaleDetails);
    updateSaleDetails();
</script>
@endsection
