<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Controller for managing invoices
 * Handles viewing, downloading, and managing invoices for patients and management
 */
class InvoiceController extends Controller
{
    /**
     * Show all invoices for the logged-in patient
     */
    public function index()
    {
        try {
            $invoices = Invoice::where('patient_id', Auth::id())
                ->orderBy('invoice_date', 'desc')
                ->get();
            
            return view('invoices.index', compact('invoices'));
        } catch (\Exception $e) {
            Log::error('Error loading patient invoices', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Er is een fout opgetreden bij het laden van uw facturen.');
        }
    }

    /**
     * Show all invoices in the system (management only)
     */
    public function all()
    {
        try {
            if (Auth::user()->role !== 'management') {
                abort(403, 'Unauthorized access');
            }

            $invoices = Invoice::with('patient')
                ->orderBy('invoice_date', 'desc')
                ->get();
            
            return view('invoices.all', compact('invoices'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            throw $e; // Re-throw authorization exceptions
        } catch (\Exception $e) {
            Log::error('Error loading all invoices', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Er is een fout opgetreden bij het laden van de facturen.');
        }
    }

    /**
     * Show a specific invoice
     * Patients can only view their own invoices
     */
    public function show($id)
    {
        try {
            $invoice = Invoice::with('patient', 'appointment')->findOrFail($id);
            
            if (Auth::user()->role === 'patient' && $invoice->patient_id !== Auth::id()) {
                abort(403, 'Unauthorized access');
            }
            
            return view('invoices.show', compact('invoice'));
        } catch (ModelNotFoundException $e) {
            Log::warning('Invoice not found', ['invoice_id' => $id, 'user_id' => Auth::id()]);
            return redirect()->route('invoices.index')->with('error', 'Factuur niet gevonden.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            throw $e; // Re-throw authorization exceptions
        } catch (\Exception $e) {
            Log::error('Error loading invoice', [
                'invoice_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('invoices.index')->with('error', 'Er is een fout opgetreden bij het laden van de factuur.');
        }
    }

    /**
     * Download invoice as PDF/printable view
     * Patients can only download their own invoices
     */
    public function download($id)
    {
        try {
            $invoice = Invoice::with('patient', 'appointment')->findOrFail($id);
            
            if (Auth::user()->role === 'patient' && $invoice->patient_id !== Auth::id()) {
                abort(403, 'Unauthorized access');
            }

            return view('invoices.pdf', compact('invoice'));
        } catch (ModelNotFoundException $e) {
            Log::warning('Invoice not found for download', ['invoice_id' => $id, 'user_id' => Auth::id()]);
            return redirect()->route('invoices.index')->with('error', 'Factuur niet gevonden.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            throw $e; // Re-throw authorization exceptions
        } catch (\Exception $e) {
            Log::error('Error downloading invoice', [
                'invoice_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('invoices.index')->with('error', 'Er is een fout opgetreden bij het downloaden van de factuur.');
        }
    }

    /**
     * Delete an invoice (management only)
     */
    public function destroy($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            
            if (Auth::user()->role !== 'management') {
                abort(403, 'Unauthorized access');
            }

            $invoiceNumber = $invoice->invoice_number;
            $invoice->delete();
            
            Log::info('Invoice deleted successfully', [
                'invoice_id' => $id,
                'invoice_number' => $invoiceNumber,
                'deleted_by' => Auth::id()
            ]);

            return redirect()->route('invoices.all')->with('success', "Factuur {$invoiceNumber} is succesvol verwijderd.");
        } catch (ModelNotFoundException $e) {
            Log::warning('Invoice not found for deletion', ['invoice_id' => $id]);
            return redirect()->route('invoices.all')->with('error', 'Factuur niet gevonden.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            throw $e; // Re-throw authorization exceptions
        } catch (\Exception $e) {
            Log::error('Error deleting invoice', [
                'invoice_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('invoices.all')->with('error', 'Er is een fout opgetreden bij het verwijderen van de factuur.');
        }
    }
}
