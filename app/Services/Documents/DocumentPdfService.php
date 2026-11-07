<?php

namespace App\Services\Documents;

use App\Models\BuyerPurchaseOrder;
use App\Models\Inspection;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

class DocumentPdfService
{
    public function purchaseOrderPdf(BuyerPurchaseOrder $order): string
    {
        $order->load(['buyer', 'items']);

        return UnicodePdf::loadView('pdf.purchase-order', [
            'order' => $order,
        ])->output();
    }

    public function inspectionPdf(Inspection $inspection): string
    {
        $inspection->load('defects');

        return UnicodePdf::loadView('pdf.inspection', [
            'inspection' => $inspection,
        ])->output();
    }
}
