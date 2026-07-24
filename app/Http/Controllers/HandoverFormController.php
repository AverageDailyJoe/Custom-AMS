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
}
