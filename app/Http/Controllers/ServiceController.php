<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Inertia\Inertia;

class ServiceController extends AbstractPortfolioController
{
    public function show(string $slug)
    {
        $service = Service::getActiveServices()
            ->bySlug($slug)
            ->with([
                'projects' => function ($query) {
                    $query->active()->latest('completed_at')->take(6);
                },
                'skills' => function ($query) {
                    $query->active()->ordered();
                }
            ])
            ->firstOrFail();

        $relatedServices = Service::getActiveServices()
            ->where('id', '!=', $service->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Portfolio/ServiceDetail', [
            'data' => $this->getPortfolioData(),
            'service' => [
                'id' => $service->id,
                'slug' => $service->slug,
                'icon' => $service->icon,
                'title' => $service->title,
                'description' => $service->description,
                'technologies' => $service->technologies,
                'features' => $service->features,
                'image_url' => $service->image_url,
                'projects' => $service->projects->map(fn($project) => [
                    'id' => $project->id,
                    'title' => $project->title,
                    'description' => $project->description,
                    'technologies' => $project->technologies,
                    'category' => $project->category,
                    'thumbnail_url' => $project->thumbnail_url,
                    'github_url' => $project->github_url,
                    'live_url' => $project->live_url,
                ])->all(),
                'skills' => $service->skills->map(fn($skill) => [
                    'name' => $skill->name,
                    'category' => $skill->category,
                    'proficiency_level' => $skill->proficiency_level,
                ])->all(),
            ],
            'relatedServices' => $relatedServices->map(fn($related) => [
                'id' => $related->id,
                'slug' => $related->slug,
                'icon' => $related->icon,
                'title' => $related->title,
                'description' => $related->description,
                'image_url' => $related->image_url,
                'technologies' => $related->technologies,
            ])->all(),
            'translations' => $this->translations(),
        ]);
    }
}
