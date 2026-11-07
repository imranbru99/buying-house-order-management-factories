<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\BuyerPurchaseOrder;
use App\Models\Document;
use App\Services\Documents\DocumentPdfService;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DocumentController extends Controller
{
    public function __construct(protected DocumentPdfService $documentPdfService) {}

    public function index(): AnonymousResourceCollection
    {
        return DocumentResource::collection(
            Document::query()->latest()->paginate()
        );
    }

    public function purchaseOrderPdf(BuyerPurchaseOrder $order): Response
    {
        $pdf = $this->documentPdfService->purchaseOrderPdf($order);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="po-'.$order->number.'.pdf"',
        ]);
    }
}
