<?php

namespace App\Filament\Resources\AssetResource\RelationManagers;

use App\Models\PengajuanAset;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PengajuanAsetsRelationManager extends RelationManager
{
    protected static string $relationship = 'pengajuanAsets';

    protected static ?string $title = 'Riwayat Pengajuan & Pembelian Komponen';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('request_number')
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('No. Pengajuan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('request_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Pengajuan')
                    ->searchable()
                    ->limit(35),

                Tables\Columns\TextColumn::make('requester_name')
                    ->label('Pemohon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('grand_total_cost')
                    ->label('Total Biaya')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'draft' => 'gray',
                        'pending_approval' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'purchased' => 'success',
                        'completed' => 'success',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('request_date', 'desc')
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('view_pengajuan')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.pengajuan-asets.edit', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}

