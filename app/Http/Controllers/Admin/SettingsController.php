<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Update webhook settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWebhook(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'nullable|string',
            'webhook_url' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Dapatkan instance settings
        $settings = WebsiteSetting::first();
        
        if (!$settings) {
            $settings = WebsiteSetting::create([
                'site_name' => 'Website Name',
                'webhook_api_key' => $validated['api_key'] ?? null,
                'webhook_url' => $validated['webhook_url'] ?? null,
                'webhook_is_active' => $validated['is_active'] ?? true,
            ]);
        } else {
            // Update hanya kolom webhook
            $settings->update([
                'webhook_api_key' => $validated['api_key'] ?? null,
                'webhook_url' => $validated['webhook_url'] ?? null,
                'webhook_is_active' => $validated['is_active'] ?? true,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan webhook berhasil diperbarui',
                'settings' => $settings
            ]);
        }

        return back()->with('success', 'Pengaturan webhook berhasil diperbarui');
    }
} 