<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    /**
     * Show ticket creation form
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store and send ticket via email
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'subject' => 'required|string|max:200',
                'priority' => 'required|in:low,medium,high,urgent',
                'description' => 'required|string|min:10|max:2000',
                'steps_to_reproduce' => 'nullable|string|max:1000',
                'expected_behavior' => 'nullable|string|max:1000',
                'actual_behavior' => 'nullable|string|max:1000',
            ]);

            $user = Auth::user();
            
            // Prepare email data
            $ticketData = [
                'ticket_id' => 'TW-' . time() . '-' . $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_id' => $user->id,
                'subject' => $validated['subject'],
                'priority' => $validated['priority'],
                'description' => $validated['description'],
                'steps_to_reproduce' => $validated['steps_to_reproduce'] ?? '',
                'expected_behavior' => $validated['expected_behavior'] ?? '',
                'actual_behavior' => $validated['actual_behavior'] ?? '',
                'browser' => $request->header('User-Agent'),
                'ip_address' => $request->ip(),
                'timestamp' => now()->format('Y-m-d H:i:s T'),
                'dashboard_url' => route('dashboard'),
            ];

            // Send email to admin
            Mail::send('emails.ticket', $ticketData, function ($message) use ($ticketData) {
                $message->to('dieuhanhweb@gmail.com')
                    ->subject('[TopWriter Support] ' . $ticketData['priority'] . ' - ' . $ticketData['subject'])
                    ->replyTo($ticketData['user_email'], $ticketData['user_name']);
            });

            Log::info('Support ticket sent', [
                'ticket_id' => $ticketData['ticket_id'],
                'user_id' => $user->id,
                'subject' => $validated['subject'],
                'priority' => $validated['priority']
            ]);

            return redirect()->route('dashboard')->with('success', 
                'Support ticket submitted successfully! We will respond within 24 hours. Ticket ID: ' . $ticketData['ticket_id']);

        } catch (\Exception $e) {
            Log::error('Failed to send support ticket', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->with('error', 
                'An error occurred while sending the ticket. Please try again later or contact us directly through the dashboard.');
        }
    }
} 