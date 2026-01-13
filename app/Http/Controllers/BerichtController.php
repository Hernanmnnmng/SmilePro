<?php

namespace App\Http\Controllers;

use App\Models\Bericht;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Controller for managing messages (berichten)
 * Handles viewing, sending, and managing messages between users
 */
class BerichtController extends Controller
{
    /**
     * Show message overview (all messages received)
     */
    public function index()
    {
        try {
            $berichten = Bericht::where('recipient_id', Auth::id())
                ->with('sender')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Count unread messages
            $unreadCount = Bericht::where('recipient_id', Auth::id())
                ->where('is_read', false)
                ->count();
            
            return view('berichten.index', compact('berichten', 'unreadCount'));
        } catch (\Exception $e) {
            Log::error('Error loading messages', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('dashboard')->with('error', 'Er is een fout opgetreden bij het laden van uw berichten.');
        }
    }

    /**
     * Show the form to send a new message
     */
    public function create()
    {
        try {
            // Get all users except the current user
            $users = User::where('id', '!=', Auth::id())
                ->orderBy('name')
                ->get();
            
            return view('berichten.create', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error loading send message form', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('berichten.index')->with('error', 'Er is een fout opgetreden.');
        }
    }

    /**
     * Store a new message
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'recipient_id' => 'required|exists:users,id|not_in:' . Auth::id(),
                'subject' => 'required|string|max:255',
                'body' => 'required|string|min:1',
            ], [
                'recipient_id.required' => 'Selecteer een ontvanger.',
                'recipient_id.exists' => 'Geselecteerde ontvanger bestaat niet.',
                'recipient_id.not_in' => 'U kunt geen bericht naar uzelf sturen.',
                'subject.required' => 'Onderwerp is verplicht.',
                'subject.max' => 'Onderwerp mag maximaal 255 tekens zijn.',
                'body.required' => 'Bericht inhoud is verplicht.',
            ]);

            $bericht = Bericht::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $validated['recipient_id'],
                'subject' => $validated['subject'],
                'body' => $validated['body'],
            ]);

            return redirect()->route('berichten.index')->with('success', 'Bericht succesvol verzonden!');
        } catch (\Exception $e) {
            Log::error('Error sending message', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Er is een fout opgetreden bij het verzenden van uw bericht.');
        }
    }

    /**
     * Show a single message
     */
    public function show($id)
    {
        try {
            $bericht = Bericht::find($id);

            if (!$bericht) {
                return redirect()->route('berichten.index')->with('error', 'Bericht niet gevonden.');
            }

            // Check if user is recipient or sender
            if ($bericht->recipient_id !== Auth::id() && $bericht->sender_id !== Auth::id()) {
                abort(403, 'U hebt geen toegang tot dit bericht.');
            }

            // Mark as read if viewing as recipient
            if ($bericht->recipient_id === Auth::id() && !$bericht->is_read) {
                $bericht->markAsRead();
            }

            return view('berichten.show', compact('bericht'));
        } catch (\Exception $e) {
            Log::error('Error loading message', [
                'user_id' => Auth::id(),
                'bericht_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('berichten.index')->with('error', 'Er is een fout opgetreden.');
        }
    }

    /**
     * Delete a message
     */
    public function destroy($id)
    {
        try {
            $bericht = Bericht::find($id);

            if (!$bericht) {
                return redirect()->route('berichten.index')->with('error', 'Bericht niet gevonden.');
            }

            // Check if user is recipient or sender
            if ($bericht->recipient_id !== Auth::id() && $bericht->sender_id !== Auth::id()) {
                abort(403, 'U hebt geen toegang tot dit bericht.');
            }

            $bericht->delete();

            return redirect()->route('berichten.index')->with('success', 'Bericht verwijderd.');
        } catch (\Exception $e) {
            Log::error('Error deleting message', [
                'user_id' => Auth::id(),
                'bericht_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Er is een fout opgetreden bij het verwijderen van het bericht.');
        }
    }

    /**
     * Mark message as read
     */
    public function markAsRead($id)
    {
        try {
            $bericht = Bericht::find($id);

            if (!$bericht) {
                return response()->json(['error' => 'Bericht niet gevonden'], 404);
            }

            if ($bericht->recipient_id !== Auth::id()) {
                return response()->json(['error' => 'Niet geauthoriseerd'], 403);
            }

            if (!$bericht->is_read) {
                $bericht->markAsRead();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking message as read', [
                'user_id' => Auth::id(),
                'bericht_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Er is een fout opgetreden'], 500);
        }
    }

    /**
     * Get sent messages
     */
    public function sent()
    {
        try {
            $berichten = Bericht::where('sender_id', Auth::id())
                ->with('recipient')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('berichten.sent', compact('berichten'));
        } catch (\Exception $e) {
            Log::error('Error loading sent messages', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('dashboard')->with('error', 'Er is een fout opgetreden bij het laden van verzonden berichten.');
        }
    }
}
