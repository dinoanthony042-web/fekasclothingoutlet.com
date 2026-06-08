@php
    $sizeMappings = config('sizes.mappings');
@endphp

<!-- Size Reference Modal -->
<div x-data="{ open: false }" @keydown.escape="open = false">
    <!-- Trigger Button -->
    <button @click="open = true" type="button" class="text-sm font-semibold uppercase tracking-[0.15em] text-[#5b1e7e] hover:text-[#1b1b18] transition">
        📏 Size Guide
    </button>

    <!-- Modal Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/40"
         @click="open = false"
         style="display: none;">
    </div>

    <!-- Modal Container -->
    <div x-show="open"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        
        <!-- Modal Content -->
        <div class="bg-white rounded-[2rem] shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b border-[#E7DDD4] p-6 flex items-center justify-between rounded-t-[2rem]">
                <div>
                    <h3 class="text-2xl font-semibold text-[#1b1b18]">Size Reference Guide</h3>
                    <p class="text-xs uppercase tracking-[0.15em] text-[#8c7d74] mt-1">UK & Turkish Sizes</p>
                </div>
                <button @click="open = false" type="button" class="text-[#8c7d74] hover:text-[#1b1b18] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-6">
                <p class="text-sm text-[#6e625d]">
                    Not sure about standard sizes? Use this guide to find your UK or Turkish size equivalent.
                </p>

                <!-- Sizing Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#E7DDD4]">
                                <th class="text-left py-3 px-4 font-semibold text-[#1b1b18]">Standard</th>
                                <th class="text-left py-3 px-4 font-semibold text-[#1b1b18]">UK Size</th>
                                <th class="text-left py-3 px-4 font-semibold text-[#1b1b18]">Turkish Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sizeMappings as $size => $mapping)
                                <tr class="border-b border-[#f0ebe7] hover:bg-[#faf5ff] transition">
                                    <td class="py-3 px-4 font-medium text-[#5b1e7e]">{{ $size }}</td>
                                    <td class="py-3 px-4 text-[#6e625d]">{{ $mapping['uk'] }}</td>
                                    <td class="py-3 px-4 text-[#6e625d]">{{ $mapping['turkish'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tip -->
                <div class="rounded-lg bg-[#faf5ff] border border-[#e6d9f5] p-4">
                    <p class="text-sm text-[#5b1e7e]">
                        <strong>💡 Tip:</strong> If you're between sizes, we recommend going up one size for a more comfortable fit. Most items can be tailored for the perfect fit.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-[#f9f4f0] border-t border-[#E7DDD4] p-6 rounded-b-[2rem]">
                <button @click="open = false" type="button" class="w-full rounded-full bg-[#1b1b18] text-white font-semibold py-3 transition hover:bg-[#403c39]">
                    Got it!
                </button>
            </div>
        </div>
    </div>
</div>

