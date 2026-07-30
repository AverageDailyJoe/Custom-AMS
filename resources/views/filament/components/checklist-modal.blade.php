<div class="p-2 space-y-4">
    <div class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden">
        <table class="w-full text-xs text-left border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 uppercase font-bold border-b border-gray-300 dark:border-gray-700">
                <tr>
                    <th class="p-2 text-center w-12 border-r border-gray-300 dark:border-gray-700">No</th>
                    <th class="p-2 border-r border-gray-300 dark:border-gray-700">Komponen Hardware</th>
                    <th class="p-2 text-center w-24 border-r border-gray-300 dark:border-gray-700">Kondisi</th>
                    <th class="p-2">Keterangan / Catatan Pengecekan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @php
                    $items = [
                        ['no' => 1, 'name' => 'Layar / Display', 'key' => 'layar'],
                        ['no' => 2, 'name' => 'Keyboard', 'key' => 'keyboard'],
                        ['no' => 3, 'name' => 'RAM / Memory', 'key' => 'ram'],
                        ['no' => 4, 'name' => 'SSD / Storage', 'key' => 'ssd'],
                        ['no' => 5, 'name' => 'Trackpad / Mouse', 'key' => 'trackpad'],
                        ['no' => 6, 'name' => 'Baterai', 'key' => 'baterai'],
                        ['no' => 7, 'name' => 'Hardware & CPU', 'key' => 'hardware'],
                        ['no' => 8, 'name' => 'Charger / Power Brick', 'key' => 'charger'],
                    ];
                @endphp
                @foreach($items as $item)
                    @php
                        $status = $checklist[$item['key'] . '_status'] ?? 'baik';
                        $notes = $checklist[$item['key'] . '_notes'] ?? '-';
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="p-2 text-center font-bold border-r border-gray-200 dark:border-gray-700">{{ $item['no'] }}</td>
                        <td class="p-2 font-semibold border-r border-gray-200 dark:border-gray-700">{{ $item['name'] }}</td>
                        <td class="p-2 text-center border-r border-gray-200 dark:border-gray-700">
                            @if($status === 'baik')
                                <span class="px-2 py-0.5 text-xs font-bold text-emerald-700 bg-emerald-100 rounded dark:bg-emerald-950 dark:text-emerald-300">Baik ✓</span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-bold text-red-700 bg-red-100 rounded dark:bg-red-950 dark:text-red-300">Rusak ✗</span>
                            @endif
                        </td>
                        <td class="p-2 text-gray-800 dark:text-gray-200">{{ $notes ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
