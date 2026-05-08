@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="relative">
    <section class="space-y-6 lg:grid lg:grid-cols-[300px_1fr] lg:gap-8">
        <!-- Desktop Filters Sidebar -->
        <aside class="hidden lg:block">
            <div class="sticky top-6 rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Filters</h2>
                <form method="get" action="{{ route('shop.index') }}" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Search</label>
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search products" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Category</label>
                        <select name="category" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none">
                            <option value="">All</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected($activeCategorySlug === $category->slug)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(isset($subcategories) && $subcategories->isNotEmpty())
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Subcategory</label>
                        <select name="subcategory" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none">
                            <option value="">All subcategories</option>
                            @foreach($subcategories as $subcategory)
                            <option value="{{ $subcategory->slug }}" @selected(request('subcategory') === $subcategory->slug)>{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="grid gap-3 grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Min price</label>
                            <input type="number" name="price_min" value="{{ request('price_min') }}" min="0" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Max price</label>
                            <input type="number" name="price_max" value="{{ request('price_max') }}" min="0" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Size</label>
                        <select name="size" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none">
                            <option value="">Any</option>
                            @foreach($sizes as $size)
                            <option value="{{ $size }}" @selected(request('size') === $size)>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Color</label>
                        <select name="color" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none">
                            <option value="">Any</option>
                            @foreach($colors as $color)
                            <option value="{{ $color }}" @selected(request('color') === $color)>{{ $color }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Style</label>
                        <select name="style" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none">
                            <option value="">Any</option>
                            @foreach($styles as $style)
                            <option value="{{ $style }}" @selected(request('style') === $style)>{{ $style }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Apply Filters</button>
                </form>
            </div>
        </aside>

        <div id="filterOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 px-4 py-8">
            <div id="filterBackdrop" class="absolute inset-0"></div>
            <aside class="relative z-10 w-full max-w-md rounded-2xl bg-white border border-slate-200 p-6 shadow-lg">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                    <h2 class="text-base font-semibold text-slate-900">Filters</h2>
                    <button id="filterClose" type="button" class="text-sm font-semibold text-slate-700 hover:text-slate-900">Close</button>
                </div>

                <form method="get" action="{{ route('shop.index') }}" class="mt-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Search</label>
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search products" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Category</label>
                        <select name="category" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none">
                            <option value="">All</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected($activeCategorySlug === $category->slug)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(isset($subcategories) && $subcategories->isNotEmpty())
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Subcategory</label>
                        <select name="subcategory" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none">
                            <option value="">All subcategories</option>
                            @foreach($subcategories as $subcategory)
                            <option value="{{ $subcategory->slug }}" @selected(request('subcategory') === $subcategory->slug)>{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Min price</label>
                            <input type="number" name="price_min" value="{{ request('price_min') }}" min="0" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Max price</label>
                            <input type="number" name="price_max" value="{{ request('price_max') }}" min="0" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Size</label>
                        <select name="size" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none">
                            <option value="">Any</option>
                            @foreach($sizes as $size)
                            <option value="{{ $size }}" @selected(request('size') === $size)>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Color</label>
                        <select name="color" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none">
                            <option value="">Any</option>
                            @foreach($colors as $color)
                            <option value="{{ $color }}" @selected(request('color') === $color)>{{ $color }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Style</label>
                        <select name="style" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none">
                            <option value="">Any</option>
                            @foreach($styles as $style)
                            <option value="{{ $style }}" @selected(request('style') === $style)>{{ $style }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Apply</button>
                </form>
            </aside>
        </div>

        <!-- Main Content -->
        <div class="space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Shop</p>
                    <h1 class="mt-1 text-3xl font-semibold text-slate-900">All products</h1>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button id="filterToggle" type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-50 lg:hidden">
                        <span class="text-lg">☰</span>
                        <span>Filters</span>
                    </button>
                    <span class="text-sm text-slate-600">Showing {{ $products->count() }} of {{ $products->total() }} pieces</span>
                </div>
            </div>

            <div class="mt-4 block lg:hidden">
                <div class="overflow-x-auto pb-2">
                    <div class="inline-flex gap-2">
                        @if($activeCategorySlug && isset($subcategories) && $subcategories->isNotEmpty())
                            <!-- Back to all categories -->
                            <a href="{{ route('shop.index') }}"
                               class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-400 hover:text-slate-900">
                                ← All Categories
                            </a>
                            
                            <!-- Show subcategories -->
                            @foreach($subcategories as $subcategory)
                                <a href="{{ route('shop.index', ['category' => $activeCategorySlug, 'subcategory' => $subcategory->slug]) }}"
                                   class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-400 hover:text-slate-900 {{ request('subcategory') === $subcategory->slug ? 'border-purple-600 bg-purple-50 text-purple-700' : '' }}">
                                    {{ $subcategory->name }}
                                </a>
                            @endforeach
                        @else
                            <!-- Show main categories -->
                            @foreach($categories as $category)
                                <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                                   class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-400 hover:text-slate-900 {{ $activeCategorySlug === $category->slug ? 'border-purple-600 bg-purple-50 text-purple-700' : '' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-600">No products matched your filters. Try a different search.</div>
            @else
                <div class="grid gap-4 grid-cols-2 sm:grid-cols-3 xl:grid-cols-5">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div class="flex justify-center pt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var filterToggle = document.getElementById('filterToggle');
        var filterOverlay = document.getElementById('filterOverlay');
        var filterClose = document.getElementById('filterClose');
        var filterBackdrop = document.getElementById('filterBackdrop');

        if (filterToggle && filterOverlay) {
            filterToggle.addEventListener('click', function () {
                filterOverlay.classList.remove('hidden');
            });
        }

        if (filterClose) {
            filterClose.addEventListener('click', function () {
                filterOverlay.classList.add('hidden');
            });
        }

        if (filterBackdrop) {
            filterBackdrop.addEventListener('click', function () {
                filterOverlay.classList.add('hidden');
            });
        }

        document.querySelectorAll('select[name="category"]').forEach(function (select) {
            select.addEventListener('change', function () {
                var subcategorySelect = select.form.querySelector('select[name="subcategory"]');
                if (subcategorySelect) {
                    subcategorySelect.value = '';
                }
            });
        });
    });
</script>
@endsection
