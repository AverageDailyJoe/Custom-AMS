<?php

namespace App\Http\Controllers;

use App\Models\Checkout;

class HandoverFormController extends Controller
{
    public function downloadHandover(Checkout $checkout)
    {
        $checkout->load(['asset.assetModel.category', 'asset.location', 'checkedOutByUser', 'checkedInByUser']);
        return view('pdf.handover-form', compact('checkout'));
    }

    public function downloadReturn(Checkout $checkout)
    {
        $checkout->load(['asset.assetModel.category', 'asset.location', 'checkedOutByUser', 'checkedInByUser']);
        return view('pdf.return-form', compact('checkout'));
    }

    public function downloadBeritaAcara(\App\Models\BeritaAcara $beritaAcara)
    {
        $beritaAcara->load(['asset.assetModel.category', 'createdBy']);
        return view('pdf.berita-acara-form', compact('beritaAcara'));
    }

    public function downloadPengajuanAset(\App\Models\PengajuanAset $pengajuanAset)
    {
        $pengajuanAset->load(['createdBy']);
        return view('pdf.pengajuan-aset-form', compact('pengajuanAset'));
    }

    public function downloadLBS(\App\Models\PengajuanAset $pengajuanAset)
    {
        $pengajuanAset->load(['createdBy']);
        return view('pdf.lbs-form', compact('pengajuanAset'));
    }

    public function downloadDisposal(\App\Models\DisposeAset $disposeAset)
    {
        $disposeAset->load(['asset.assetModel.category', 'createdBy']);
        return view('pdf.disposal-form', compact('disposeAset'));
    }

    public function downloadTicket(\App\Models\Ticket $ticket)
    {
        $ticket->load(['location', 'asset.assetModel.category', 'assignedToUser', 'createdBy']);
        return view('pdf.ticket-form', compact('ticket'));
    }
}
