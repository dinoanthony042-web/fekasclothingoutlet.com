@php
    $chart = config('sizes.chart', []);
    $sizeMappings = config('sizes.mappings', []);
@endphp

<div x-data="{ open: false }" class="inline-block">
    <button type="button" @click="open = true" class="text-sm font-semibold text-[#5b1e7e] hover:text-[#1b1b18]">📏 Size Guide</button>

    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.away="open = false" class="max-w-3xl w-full rounded-2xl bg-white p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Size Guide</h3>
                <button @click="open = false" class="text-sm text-gray-600">Close</button>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full table-auto text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">Standard</th>
                            <th class="py-2">Turkey</th>
                            <th class="py-2">USA</th>
                            <th class="py-2">UK</th>
                            <th class="py-2">Africa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chart as $row)
                            <tr class="border-b last:border-b-0">
                                <td class="py-2 font-semibold">{{ $row['label'] ?? '' }}</td>
                                <td class="py-2">{{ $row['turkey'] ?? '' }}</td>
                                <td class="py-2">{{ $row['usa'] ?? '' }}</td>
                                <td class="py-2">{{ $row['uk'] ?? '' }}</td>
                                <td class="py-2">{{ $row['africa'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-right">
                <button @click="open = false" class="inline-flex items-center justify-center rounded-full bg-[#1b1b18] px-5 py-2 text-sm font-semibold text-white">Close</button>
            </div>
        </div>
    </div>
</div>

