<?php

namespace Modules\BIRReceiptPlugin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\BIRReceiptPlugin\Models\BIRReceiptTemplate;
use Modules\BIRReceiptPlugin\Models\BIRReceiptSetting;
use Modules\BIRReceiptPlugin\Models\BIRReceiptCustomization;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class BIRReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $templates = BIRReceiptTemplate::getActive();
        $settings = BIRReceiptSetting::getDefault();
        
        return view('birreceiptplugin::index', compact('templates', 'settings'));
    }

    /**
     * Get BIR templates for AJAX requests
     * @return Response
     */
    public function getTemplates()
    {
        $templates = BIRReceiptTemplate::getActive();
        
        return response()->json([
            'templates' => $templates->map(function($template) {
                return [
                    'template_code' => $template->template_code,
                    'template_name' => $template->template_name,
                    'description' => $template->description,
                    'is_active' => $template->is_active
                ];
            })
        ]);
    }

    /**
     * Generate BIR receipt for a transaction
     * @param Request $request
     * @return Response
     */
    public function generateReceipt(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
            'template_code' => 'required|string',
            'format' => 'in:html,pdf,print'
        ]);

        $templateCode = $request->input('template_code', 'A1');
        $transactionId = $request->input('transaction_id');
        $format = $request->input('format', 'html');

        // Get template
        $template = BIRReceiptTemplate::getByCode($templateCode);
        if (!$template) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        // Get settings
        $settings = BIRReceiptSetting::getDefault();
        if (!$settings) {
            return response()->json(['error' => 'BIR settings not configured'], 400);
        }

        // Get customization
        $customization = BIRReceiptCustomization::getDefaultForTemplate($templateCode);

        // Prepare receipt data
        $receiptData = $this->prepareReceiptData($request, $settings, $template, $customization);

        // Generate receipt based on format
        switch ($format) {
            case 'pdf':
                return $this->generatePDF($receiptData, $template, $customization);
            case 'print':
                return $this->generatePrintView($receiptData, $template, $customization);
            default:
                return $this->generateHTML($receiptData, $template, $customization);
        }
    }

    /**
     * Show template customization interface
     * @param string $templateCode
     * @return Response
     */
    public function customizeTemplate($templateCode)
    {
        $template = BIRReceiptTemplate::getByCode($templateCode);
        if (!$template) {
            abort(404, 'Template not found');
        }

        $customization = BIRReceiptCustomization::getDefaultForTemplate($templateCode);
        $settings = BIRReceiptSetting::getDefault();

        return view('birreceiptplugin::customize', compact('template', 'customization', 'settings'));
    }

    /**
     * Save template customization
     * @param Request $request
     * @param string $templateCode
     * @return Response
     */
    public function saveCustomization(Request $request, $templateCode)
    {
        $request->validate([
            'customization_name' => 'required|string|max:255',
            'layout_settings' => 'array',
            'field_settings' => 'array',
            'style_settings' => 'array',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);

        $customization = BIRReceiptCustomization::updateOrCreate(
            [
                'template_code' => $templateCode,
                'business_id' => null, // For now, use global settings
                'is_default' => true,
            ],
            [
                'customization_name' => $request->input('customization_name'),
                'description' => $request->input('description'),
                'layout_settings' => $request->input('layout_settings', []),
                'field_settings' => $request->input('field_settings', []),
                'style_settings' => $request->input('style_settings', []),
                'custom_css' => $request->input('custom_css'),
                'custom_js' => $request->input('custom_js'),
                'is_active' => true,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Customization saved successfully',
            'customization' => $customization
        ]);
    }

    /**
     * Show BIR settings configuration
     * @return Response
     */
    public function showSettings()
    {
        $settings = BIRReceiptSetting::getDefault();
        return view('birreceiptplugin::settings', compact('settings'));
    }

    /**
     * Save BIR settings
     * @param Request $request
     * @return Response
     */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'tin_number' => 'required|string|max:20',
            'business_name' => 'required|string|max:255',
            'business_address' => 'required|string',
            'business_phone' => 'nullable|string|max:20',
            'business_email' => 'nullable|email|max:255',
            'business_website' => 'nullable|url|max:255',
            'default_template' => 'required|string',
        ]);

        $settings = BIRReceiptSetting::updateOrCreate(
            ['business_id' => null, 'is_active' => true],
            [
                'tin_number' => $request->input('tin_number'),
                'business_name' => $request->input('business_name'),
                'business_address' => $request->input('business_address'),
                'business_phone' => $request->input('business_phone'),
                'business_email' => $request->input('business_email'),
                'business_website' => $request->input('business_website'),
                'logo_path' => $request->input('logo_path'),
                'header_text' => $request->input('header_text'),
                'footer_text' => $request->input('footer_text'),
                'default_template' => $request->input('default_template'),
                'custom_fields' => $request->input('custom_fields', []),
                'receipt_settings' => $request->input('receipt_settings', []),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully',
            'settings' => $settings
        ]);
    }


    /**
     * Generate BIR receipt number
     * @return string
     */
    public function generateBIRReceiptNumber()
    {
        // Generate BIR-compliant receipt number
        $prefix = 'BIR';
        $year = date('Y');
        $month = date('m');
        $sequence = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . $year . $month . $sequence;
    }

    /**
     * Prepare receipt data for rendering
     */
    private function prepareReceiptData($request, $settings, $template, $customization = null)
    {
        $transactionId = $request->input('transaction_id');
        
        // Get transaction data from request
        $items = $request->input('items', []);
        $customerName = $request->input('customer_name', 'Walk-in Customer');
        $customerPhone = $request->input('customer_phone', '');
        $customerAddress = $request->input('customer_address', '');
        $subtotal = $request->input('subtotal', 0);
        $taxAmount = $request->input('tax_amount', 0);
        $totalAmount = $request->input('total_amount', 0);
        $date = $request->input('date', now()->format('Y-m-d'));
        $time = $request->input('time', now()->format('H:i:s'));
        
        // If no items provided, use sample data
        if (empty($items)) {
            $items = [
                [
                    'name' => 'Sample Item',
                    'quantity' => 1,
                    'unit_price' => 100.00,
                    'total' => 100.00,
                ]
            ];
            $subtotal = 100.00;
            $taxAmount = 12.00;
            $totalAmount = 112.00;
        }
        
        return [
            'receipt_number' => $this->generateBIRReceiptNumber(),
            'date' => $date . ' ' . $time,
            'business' => $settings,
            'template' => $template,
            'customization' => $customization,
            'transaction' => [
                'id' => $transactionId,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'customer_address' => $customerAddress,
                'customer_tin' => '',
                'items' => $items,
                'subtotal' => $subtotal,
                'vat_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ]
        ];
    }

    /**
     * Generate HTML receipt
     */
    private function generateHTML($receiptData, $template, $customization)
    {
        $view = 'birreceiptplugin::templates.' . strtolower($template->template_code);
        
        return view($view, ['receiptData' => $receiptData]);
    }

    /**
     * Generate PDF receipt
     */
    private function generatePDF($receiptData, $template, $customization)
    {
        $view = 'birreceiptplugin::templates.' . strtolower($template->template_code);
        
        $pdf = Pdf::loadView($view, ['receiptData' => $receiptData]);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('bir_receipt_' . $receiptData['receipt_number'] . '.pdf');
    }

    /**
     * Generate print view
     */
    private function generatePrintView($receiptData, $template, $customization)
    {
        $view = 'birreceiptplugin::templates.' . strtolower($template->template_code);
        
        return view('birreceiptplugin::print', [
            'receiptData' => $receiptData,
            'template' => $template,
            'customization' => $customization,
            'view' => $view
        ]);
    }
}
