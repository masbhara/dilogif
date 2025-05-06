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
            'triggerStatusOptions' => $this->getTriggerStatusOptions(),
        ]);
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        return Inertia::render('admin/notifications/Create', [
            'availableVariables' => WhatsAppTemplate::getAvailableVariables(),
            'triggerStatusOptions' => $this->getTriggerStatusOptions(),
        ]);
    }

    /**
     * Store a newly created template in storage.
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
            'order' => 'integer|min:0',
        ]);
        
        WhatsAppTemplate::create($validated);
        
        return redirect()->route('admin.notifications.index')
            ->with('success', 'Template berhasil dibuat');
    }

    /**
     * Display the specified template.
     */
    public function show(WhatsAppTemplate $template)
    {
        return Inertia::render('admin/notifications/Show', [
            'template' => $template,
            'availableVariables' => WhatsAppTemplate::getAvailableVariables(),
            'triggerStatusOptions' => $this->getTriggerStatusOptions(),
        ]);
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(WhatsAppTemplate $template)
    {
        return Inertia::render('admin/notifications/Edit', [
            'template' => $template,
            'availableVariables' => WhatsAppTemplate::getAvailableVariables(),
            'triggerStatusOptions' => $this->getTriggerStatusOptions(),
        ]);
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, WhatsAppTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message_template' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);
        
        $template->update($validated);
        
        return redirect()->route('admin.notifications.index')
            ->with('success', 'Template berhasil diperbarui');
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroy(WhatsAppTemplate $template)
    {
        $template->delete();
        
        return redirect()->route('admin.notifications.index')
            ->with('success', 'Template berhasil dihapus');
    }
    
    /**
     * Toggle the active status of the specified template.
     */
    public function toggleActive(WhatsAppTemplate $template)
    {
        $template->update([
            'is_active' => !$template->is_active
        ]);
        
        return redirect()->back()
            ->with('success', 'Status template berhasil diperbarui');
    }
    
    /**
     * Get options for trigger status dropdown.
     */
    private function getTriggerStatusOptions()
    {
        return [
            WhatsAppTemplate::TRIGGER_NEW_ORDER => 'Order Baru',
            WhatsAppTemplate::TRIGGER_PENDING => 'Menunggu Pembayaran',
            WhatsAppTemplate::TRIGGER_PROCESSING => 'Sedang Diproses',
            WhatsAppTemplate::TRIGGER_REVIEW => 'Menunggu Review',
            WhatsAppTemplate::TRIGGER_COMPLETED => 'Selesai',
            WhatsAppTemplate::TRIGGER_CANCELLED => 'Dibatalkan',
            WhatsAppTemplate::TRIGGER_PAYMENT_CONFIRMED => 'Pembayaran Dikonfirmasi',
        ];
    }
}
