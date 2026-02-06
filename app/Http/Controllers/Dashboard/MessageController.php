<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PortfolioContent;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index()
    {
        $messages = PortfolioContent::active()
            ->byType('contact_message')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(fn($message) => [
                'id' => $message->id,
                'name' => $message->getLocalizedContent('title') ?? 'Anonymous',
                'email' => $message->getLocalizedContent('email') ?? 'N/A',
                'content' => $message->getLocalizedContent('content'),
                'created_at' => $message->created_at ? $message->created_at->format('Y-m-d H:i:s') : 'N/A',
                'preview' => \Illuminate\Support\Str::limit($message->getLocalizedContent('content'), 100),
            ]);

        return Inertia::render('Dashboard/Messages/Index', [
            'messages' => $messages,
        ]);
    }

    public function show(PortfolioContent $message)
    {
        // Ensure the message is of type contact_message
        if ($message->type !== 'contact_message') {
            abort(404);
        }

        $messageData = [
            'id' => $message->id,
            'name' => $message->getLocalizedContent('title') ?? 'Anonymous',
            'email' => $message->getLocalizedContent('email') ?? 'N/A',
            'content' => $message->getLocalizedContent('content'),
            'created_at' => $message->created_at ? $message->created_at->format('Y-m-d H:i:s') : 'N/A',
        ];

        return Inertia::render('Dashboard/Messages/Show', [
            'message' => $messageData,
        ]);
    }

    public function destroy(PortfolioContent $message)
    {
        // Ensure the message is of type contact_message
        if ($message->type !== 'contact_message') {
            abort(404);
        }

        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
