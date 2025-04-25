<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class OrderDocumentController extends Controller
{
    /**
     * Tampilkan dokumen pesanan ke pelanggan
     */
    public function index(Order $order)
    {
        // Pastikan hanya pemilik pesanan yang bisa mengakses
        $this->authorize('view', $order);
        
        $order->load(['documents' => function($query) {
            $query->latest();
        }]);
        
        // Kelompokkan dokumen berdasarkan jenis
        $credentials = $order->documents->where('type', OrderDocument::TYPE_CREDENTIAL);
        $domainInfo = $order->documents->where('type', OrderDocument::TYPE_DOMAIN);
        $updates = $order->documents->where('type', OrderDocument::TYPE_UPDATE);
        $downloads = $order->documents->where('type', OrderDocument::TYPE_DOWNLOAD);
        
        return Inertia::render('orders/documents/Index', [
            'order' => $order,
            'credentials' => $credentials,
            'domainInfo' => $domainInfo,
            'updates' => $updates,
            'downloads' => $downloads,
        ]);
    }
    
    /**
     * Tampilkan detail dokumen
     */
    public function show(Order $order, OrderDocument $document)
    {
        // Pastikan hanya pemilik pesanan yang bisa mengakses
        $this->authorize('view', $order);
        $this->authorize('view', $document);
        
        return Inertia::render('orders/documents/Show', [
            'order' => $order,
            'document' => $document,
        ]);
    }
    
    /**
     * Unduh file dokumen
     */
    public function download(Order $order, OrderDocument $document)
    {
        // Pastikan hanya pemilik pesanan yang bisa mengakses
        $this->authorize('view', $order);
        $this->authorize('view', $document);
        
        if (!$document->file_path) {
            return back()->with('error', 'File tidak tersedia untuk diunduh');
        }
        
        return Storage::download($document->file_path, $document->title . ' - ' . $order->order_number . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION));
    }
} 