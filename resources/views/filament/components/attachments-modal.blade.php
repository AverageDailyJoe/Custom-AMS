<div class="space-y-4 p-2">
    @if(empty($files))
        <p class="text-sm text-gray-500">Tidak ada lampiran berkas.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($files as $file)
                @php
                    $url = asset('storage/' . $file);
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                @endphp

                <div class="border border-gray-700 rounded-lg p-3 bg-gray-800 space-y-2 flex flex-col justify-between">
                    <div class="text-xs font-mono text-gray-400 truncate" title="{{ basename($file) }}">
                        📄 {{ basename($file) }}
                    </div>

                    @if($isImage)
                        <div class="overflow-hidden rounded-md bg-gray-900 border border-gray-700 flex items-center justify-center max-h-60">
                            <img src="{{ $url }}" alt="Dokumentasi" class="object-contain max-h-56 w-full" />
                        </div>
                    @else
                        <div class="p-6 bg-gray-900 rounded-md border border-gray-700 text-center space-y-2">
                            <div class="text-3xl">📑</div>
                            <span class="text-xs text-gray-300 font-medium">Dokumen {{ strtoupper($extension) }}</span>
                        </div>
                    @endif

                    <a href="{{ $url }}" target="_blank" class="inline-flex items-center justify-center gap-1 w-full px-3 py-1.5 text-xs font-medium text-white bg-primary-600 rounded-md hover:bg-primary-500 transition">
                        <span>🔍 Buka / Download Berkas</span>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
