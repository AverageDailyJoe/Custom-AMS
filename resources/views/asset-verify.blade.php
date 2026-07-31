<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Aset IT - {{ $asset->asset_tag }}</title>
    <!-- Use Tailwind via CDN for standalone public page styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        
        <!-- Header -->
        <div class="bg-green-600 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Verified Asset
            </h2>
            <span class="text-green-100 text-sm">Valid</span>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-black text-gray-800">{{ $asset->asset_tag }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $asset->assetModel?->category?->name ?? 'Unknown Category' }} / {{ $asset->assetModel?->name ?? 'Unknown Model' }}</p>
            </div>

            <div class="space-y-4">
                
                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                    <span class="text-gray-500 text-sm font-medium">Serial Number</span>
                    <span class="text-gray-800 font-semibold">{{ $asset->serial ?: '-' }}</span>
                </div>

                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                    <span class="text-gray-500 text-sm font-medium">Status</span>
                    <span>
                        @if($asset->status === 'checked_out' || !empty($asset->primary_user))
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase">Checked Out</span>
                        @elseif($asset->status === 'in_stock')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold uppercase">In Stock</span>
                        @elseif($asset->status === 'maintenance')
                            <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-bold uppercase">Maintenance</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-bold uppercase">{{ $asset->status }}</span>
                        @endif
                    </span>
                </div>

                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                    <span class="text-gray-500 text-sm font-medium">Pengguna</span>
                    <span class="text-gray-800 font-semibold text-right">{{ $asset->holder_name }}</span>
                </div>

                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                    <span class="text-gray-500 text-sm font-medium">Lokasi</span>
                    <span class="text-gray-800 font-semibold text-right">{{ $asset->location?->name ?: '-' }}</span>
                </div>

                <div class="flex justify-between items-center pb-2">
                    <span class="text-gray-500 text-sm font-medium">Departemen</span>
                    <span class="text-gray-800 font-semibold text-right">{{ $asset->department ?: '-' }}</span>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400">Scan QR Code ini untuk memvalidasi aset secara real-time.</p>
                <p class="text-xs text-gray-400 font-semibold mt-1">Gondowangi Kosmetika IT Asset Management</p>
            </div>
        </div>
    </div>

</body>
</html>
