<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDocument;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class OrderDocumentController extends Controller
{
    protected $notificationService;
    
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Tampilkan daftar dokumen untuk pesanan
     */
    public function index(Order $order)
    {
        $order->load(['documents' => function($query) {
            $query->latest();
        }]);
        
        return Inertia::render('admin/orders/documents/Index', [
            'order' => $order,
            'documentTypes' => [
                OrderDocument::TYPE_CREDENTIAL => 'Kredensial Login',
                OrderDocument::TYPE_DOMAIN => 'Informasi Domain',
                OrderDocument::TYPE_UPDATE => 'Pembaruan',
                OrderDocument::TYPE_DOWNLOAD => 'File Unduhan',
            ],
        ]);
    }
    
    /**
     * Tampilkan form untuk membuat dokumen baru
     */
    public function create(Order $order)
    {
        return Inertia::render('admin/orders/documents/Create', [
            'order' => $order,
            'documentTypes' => [
                OrderDocument::TYPE_CREDENTIAL => 'Kredensial Login',
                OrderDocument::TYPE_DOMAIN => 'Informasi Domain',
                OrderDocument::TYPE_UPDATE => 'Pembaruan',
                OrderDocument::TYPE_DOWNLOAD => 'File Unduhan',
            ],
        ]);
    }
    
    /**
     * Simpan dokumen baru
     */
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'type' => 'required|in:credential,domain,update,download',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'expires_at' => 'nullable|date',
            'should_notify' => 'boolean',
            'file' => 'nullable|file|max:10240',
        ]);
        
        $document = $order->documents()->create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'expires_at' => $validated['expires_at'] ?? null,
        ]);
        
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('order-documents');
            $document->update(['file_path' => $path]);
        }
        
        // Kirim notifikasi jika diminta
        if ($request->boolean('should_notify', false)) {
            switch ($validated['type']) {
                case OrderDocument::TYPE_CREDENTIAL:
                    $this->notificationService->sendCredentials($document);
                    break;
                case OrderDocument::TYPE_DOMAIN:
                    $this->notificationService->sendDomainInfo($document);
                    break;
                case OrderDocument::TYPE_UPDATE:
                    $this->notificationService->sendUpdateInfo($document);
                    break;
                case OrderDocument::TYPE_DOWNLOAD:
                    $this->notificationService->sendDownloadLink($document);
                    break;
            }
        }
        
        return redirect()->route('admin.orders.documents.index', $order)
            ->with('message', 'Dokumen berhasil dibuat');
    }
    
    /**
     * Tampilkan form untuk edit dokumen
     */
    public function edit(Order $order, OrderDocument $document)
    {
        return Inertia::render('admin/orders/documents/Edit', [
            'order' => $order,
            'document' => $document,
            'documentTypes' => [
                OrderDocument::TYPE_CREDENTIAL => 'Kredensial Login',
                OrderDocument::TYPE_DOMAIN => 'Informasi Domain',
                OrderDocument::TYPE_UPDATE => 'Pembaruan',
                OrderDocument::TYPE_DOWNLOAD => 'File Unduhan',
            ],
        ]);
    }
    
    /**
     * Update dokumen
     */
    public function update(Request $request, Order $order, OrderDocument $document)
    {
        $validated = $request->validate([
            'type' => 'required|in:credential,domain,update,download',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'expires_at' => 'nullable|date',
            'should_notify' => 'boolean',
            'file' => 'nullable|file|max:10240',
        ]);
        
        $document->update([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'expires_at' => $validated['expires_at'] ?? null,
        ]);
        
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($document->file_path) {
                Storage::delete($document->file_path);
            }
            
            $path = $request->file('file')->store('order-documents');
            $document->update(['file_path' => $path]);
        }
        
        // Kirim notifikasi jika diminta
        if ($request->boolean('should_notify', false)) {
            switch ($validated['type']) {
                case OrderDocument::TYPE_CREDENTIAL:
                    $this->notificationService->sendCredentials($document);
                    break;
                case OrderDocument::TYPE_DOMAIN:
                    $this->notificationService->sendDomainInfo($document);
                    break;
                case OrderDocument::TYPE_UPDATE:
                    $this->notificationService->sendUpdateInfo($document);
                    break;
                case OrderDocument::TYPE_DOWNLOAD:
                    $this->notificationService->sendDownloadLink($document);
                    break;
            }
        }
        
        return redirect()->route('admin.orders.documents.index', $order)
            ->with('message', 'Dokumen berhasil diperbarui');
    }
    
    /**
     * Hapus dokumen
     */
    public function destroy(Order $order, OrderDocument $document)
    {
        // Hapus file jika ada
        if ($document->file_path) {
            Storage::delete($document->file_path);
        }
        
        $document->delete();
        
        return redirect()->route('admin.orders.documents.index', $order)
            ->with('message', 'Dokumen berhasil dihapus');
    }
    
    /**
     * Kirim dokumen melalui email
     */
    public function send(Order $order, OrderDocument $document)
    {
        $success = false;
        
        switch ($document->type) {
            case OrderDocument::TYPE_CREDENTIAL:
                $success = $this->notificationService->sendCredentials($document);
                break;
            case OrderDocument::TYPE_DOMAIN:
                $success = $this->notificationService->sendDomainInfo($document);
                break;
            case OrderDocument::TYPE_UPDATE:
                $success = $this->notificationService->sendUpdateInfo($document);
                break;
            case OrderDocument::TYPE_DOWNLOAD:
                $success = $this->notificationService->sendDownloadLink($document);
                break;
        }
        
        if ($success) {
            return redirect()->route('admin.orders.documents.index', $order)
                ->with('message', 'Dokumen berhasil dikirim');
        } else {
            return redirect()->route('admin.orders.documents.index', $order)
                ->with('error', 'Gagal mengirim dokumen');
        }
    }

    /**
     * Menampilkan semua dokumen order
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function allDocuments(Request $request)
    {
        $query = OrderDocument::query()
            ->with(['order', 'order.user'])
            ->latest();

        // Filter berdasarkan search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', "%{$search}%")
                                ->orWhereHas('user', function ($userQuery) use ($search) {
                                    $userQuery->where('name', 'like', "%{$search}%");
                                });
                  });
            });
        }

        $perPage = $request->get('per_page', 10);

        $documents = $query->paginate($perPage)->withQueryString();
        
        // Tambahkan append attributes
        $documents->getCollection()->each->append(['type_label', 'type_icon', 'type_color', 'file_size']);

        // Ambil daftar order yang tersedia untuk dropdown
        $availableOrders = Order::select('id', 'order_number', 'customer_name')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('admin/orders/documents/AllDocuments', [
            'documents' => $documents,
            'filters' => [
                'search' => $request->search,
                'per_page' => (int) $perPage,
            ],
            'available_orders' => $availableOrders,
        ]);
    }

    /**
     * Download dokumen
     *
     * @param OrderDocument $document
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(OrderDocument $document)
    {
        abort_if(!Storage::exists($document->file_path), 404);

        // Buat nama file yang benar untuk diunduh
        $filename = Str::slug(substr($document->title, 0, 100)) . '.' . pathinfo(Storage::path($document->file_path), PATHINFO_EXTENSION);

        return response()->download(
            Storage::path($document->file_path),
            $filename
        );
    }

    /**
     * Menampilkan detail dokumen
     */
    public function show(Order $order, OrderDocument $document)
    {
        return Inertia::render('admin/orders/documents/Show', [
            'order' => $order,
            'document' => $document,
            'documentTypes' => [
                OrderDocument::TYPE_CREDENTIAL => 'Kredensial Login',
                OrderDocument::TYPE_DOMAIN => 'Informasi Domain',
                OrderDocument::TYPE_UPDATE => 'Pembaruan',
                OrderDocument::TYPE_DOWNLOAD => 'File Unduhan',
            ],
        ]);
    }
} 