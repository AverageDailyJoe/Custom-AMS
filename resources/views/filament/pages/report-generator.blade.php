<x-filament-panels::page>
    <form wire:submit="downloadReport">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" icon="heroicon-o-arrow-down-tray" color="primary">
                Download Laporan (CSV)
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
