<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use App\Models\Project;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectCollection;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::getActiveProjects()
            ->when($request->input('search'), function ($query, $search) {
                $query->search($search);
            })
            ->when($request->input('category'), function ($query, $category) {
                $query->byCategory($category);
            })
            ->when($request->input('featured'), function ($query) {
                $query->featured();
            })
            ->get();

        // Return JSON for API requests, Inertia for web requests
        if ($request->wantsJson()) {
            return new JsonResponse(new ProjectCollection($projects));
        }

        return Inertia::render('Admin/Projects/Index', [
            'projects' => new ProjectCollection($projects),
            'filters' => $request->only(['search', 'category', 'featured']),
            'categories' => ['web', 'ai', 'iot', 'mobile', 'desktop'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Projects/Create', [
            'categories' => ['web', 'ai', 'iot', 'mobile', 'desktop'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        $project = Project::create($validated);

        return new JsonResponse(new ProjectResource($project), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['services', 'skills']);

        if (request()->wantsJson()) {
            return new JsonResponse(new ProjectResource($project));
        }

        return Inertia::render('Admin/Projects/Show', [
            'project' => new ProjectResource($project),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return Inertia::render('Admin/Projects/Edit', [
            'project' => new ProjectResource($project),
            'categories' => ['web', 'ai', 'iot', 'mobile', 'desktop'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectStoreRequest $request, Project $project): JsonResponse
    {
        $validated = $request->validated();
        
        $project->update($validated);

        return new JsonResponse(new ProjectResource($project));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return new JsonResponse(null, 204);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Project $project): JsonResponse
    {
        $project->restore();

        return new JsonResponse(new ProjectResource($project));
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete(Project $project): JsonResponse
    {
        $project->forceDelete();

        return new JsonResponse(null, 204);
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggle(Project $project): JsonResponse
    {
        $project->update(['is_active' => !$project->is_active]);
        
        return new JsonResponse(new ProjectResource($project));
    }

    /**
     * Toggle the featured status of the specified resource.
     */
    public function featured(Project $project): JsonResponse
    {
        $project->update(['is_featured' => !$project->is_featured]);
        
        return new JsonResponse(new ProjectResource($project));
    }

    /**
     * Get projects for public display (API endpoint).
     */
    public function public(Request $request): JsonResponse
    {
        $projects = Project::getActiveProjects()
            ->when($request->input('featured'), function ($query) {
                $query->featured();
            })
            ->when($request->input('category'), function ($query, $category) {
                $query->byCategory($category);
            })
            ->latest()
            ->get();

        return new JsonResponse(new ProjectCollection($projects));
    }

    /**
     * Get featured projects for portfolio display.
     */
    public function getFeatured(): JsonResponse
    {
        $projects = Project::getFeaturedProjects()->get();

        return new JsonResponse(new ProjectCollection($projects));
    }

    /**
     * Get projects by category.
     */
    public function byCategory(string $category): JsonResponse
    {
        $projects = Project::getProjectsByCategory($category)->get();

        return new JsonResponse(new ProjectCollection($projects));
    }
}
