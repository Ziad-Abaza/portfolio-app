<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\PortfolioContent;
use Illuminate\Support\Str;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch real data
        $servicesCount = Service::active()->count();
        $projectsCount = Project::active()->count();
        $skillsCount = Skill::active()->count();

        // Fetch recent projects (limit 5)
        $recentProjects = Project::active()
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($project) => [
                'id' => $project->id,
                'title' => $project->title,
                'category' => $project->category,
                'updated_at' => $project->updated_at ? $project->updated_at->format('M d, Y') : 'N/A',
                'edit_url' => '#', // TODO: Update when admin.projects.edit route exists
            ]);

        // Fetch recent messages (from PortfolioContent where type = 'contact_message')
        $recentMessages = PortfolioContent::active()
            ->byType('contact_message')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($message) => [
                'id' => $message->id,
                'name' => $message->getLocalizedContent('title') ?? 'Anonymous',
                'email' => $message->getLocalizedContent('email') ?? 'N/A',
                'created_at' => $message->created_at ? $message->created_at->format('Y-m-d H:i:s') : 'N/A',
                'preview' => Str::limit($message->getLocalizedContent('content'), 50),
                'view_url' => route('admin.messages.show', $message),
            ]);

        return Inertia::render('Dashboard/Dashboard', [
            'stats' => [
                'services' => $servicesCount,
                'projects' => $projectsCount,
                'skills' => $skillsCount,
                'messages' => PortfolioContent::active()->byType('contact_message')->count(),
            ],
            'recentProjects' => $recentProjects,
            'recentMessages' => $recentMessages,
        ]);
    }
}
