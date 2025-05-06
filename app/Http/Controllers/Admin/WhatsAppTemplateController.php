<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppTemplate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WhatsAppTemplateController extends Controller
{
    /**
     * Display a listing of the templates.
     */
    public function index()
    {
        $customerTemplates = WhatsAppTemplate::where('type', WhatsAppTemplate::TYPE_CUSTOMER)
            ->orderBy('order')
            ->get();
            
        $adminTemplates = WhatsAppTemplate::where('type', WhatsAppTemplate::TYPE_ADMIN)
            ->orderBy('order')
            ->get();
            
        return Inertia::render('admin/notifications/Index', [
            'customerTemplates' => $customerTemplates,
            'adminTemplates' => $adminTemplates,
            'availableVariables' => WhatsAppTemplate::getAvailableVariables(),
            'triggerStatusOptions' => [
                WhatsAppTemplate::TRIGGER_NEW_ORDER => 'Order Baru',
                WhatsAppTemplate::TRIGGER_PENDING => 'Menunggu Pembayaran',
                WhatsAppTemplate::TRIGGER_PROCESSING => 'Sedang Diproses',
                WhatsAppTemplate::TRIGGER_REVIEW => 'Menunggu Review',
                WhatsAppTemplate::TRIGGER_COMPLETED => 'Selesai',
                WhatsAppTemplate::TRIGGER_CANCELLED => 'Dibatalkan',
                WhatsAppTemplate::TRIGGER_PAYMENT_CONFIRMED => 'Pembayaran Dikonfirmasi',
            ],
        ]);
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:customer,admin',
            'trigger_status' => 'required|string',
            'message_template' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        // Periksa apakah template dengan tipe dan trigger_status yang sama sudah ada
        $existingTemplate = WhatsAppTemplate::where('type', $validated['type'])
            ->where('trigger_status', $validated['trigger_status'])
            ->first();
            
        if ($existingTemplate) {
            return back()->withErrors([
                'trigger_status' => 'Template dengan status trigger ini sudah ada'
            ]);
        }

        WhatsAppTemplate::create($validated);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Template berhasil dibuat');
    }

    /**
     * Update the specified template.
     */
    public function update(Request $request, WhatsAppTemplate $whatsAppTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message_template' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        $whatsAppTemplate->update($validated);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Template berhasil diperbarui');
    }

    /**
     * Remove the specified template.
     */
    public function destroy(WhatsAppTemplate $whatsAppTemplate)
    {
        $whatsAppTemplate->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Template berhasil dihapus');
    }

    /**
     * Toggle active status of the template.
     */
    public function toggleActive(WhatsAppTemplate $whatsAppTemplate)
    {
        $whatsAppTemplate->update([
            'is_active' => !$whatsAppTemplate->is_active,
        ]);

        return back()->with('success', 'Status template berhasil diubah');
    }
}
