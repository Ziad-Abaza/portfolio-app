<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProjectController extends Controller
{
  /**
   * Display a listing of the projects.
   */
  public function index(Request $request)
  {
    $query = Project::query();

    // Search
    if ($search = $request->input('search')) {
      $query->where(function ($q) use ($search) {
        $q->where('title_en', 'like', "%{$search}%")
          ->orWhere('title_ar', 'like', "%{$search}%")
          ->orWhere('description_en', 'like', "%{$search}%")
          ->orWhere('category', 'like', "%{$search}%");
      });
    }

    // Filter by category
    if ($category = $request->input('category')) {
      $query->where('category', $category);
    }

    $projects = $query
      ->orderBy('sort_order')
      ->orderBy('completed_at', 'desc')
      ->paginate(15)
      ->withQueryString();

    $categories = Project::distinct()->pluck('category')->filter();

    return Inertia::render('Dashboard/Projects/Index', [
      'projects' => $projects,
      'filters' => $request->only(['search', 'category']),
      'categories' => $categories,
    ]);
  }

  /**
   * Show the form for creating a new project.
   */
  public function create()
  {
    $services = Service::active()->get(['id', 'title', 'slug']);
    $skills = Skill::active()->get(['id', 'name', 'slug']);

    return Inertia::render('Dashboard/Projects/Create', [
      'services' => $services,
      'skills' => $skills,
    ]);
  }

  /**
   * Store a newly created project in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'title_en' => 'required|string|max:255',
      'title_ar' => 'nullable|string|max:255',
      'description_en' => 'required|string',
      'description_ar' => 'nullable|string',
      'content_en' => 'nullable|string',
      'content_ar' => 'nullable|string',
      'category' => 'required|string|max:100',
      'is_featured' => 'boolean',
      'is_active' => 'boolean',
      'sort_order' => 'integer|min:0',
      'completed_at' => 'nullable|date',
      'technologies_en' => 'nullable|array',
      'technologies_en.*' => 'string|max:100',
      'technologies_ar' => 'nullable|array',
      'technologies_ar.*' => 'string|max:100',
      'github_url' => 'nullable|url',
      'live_url' => 'nullable|url',
      'demo_url' => 'nullable|url',
      'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      'tags' => 'nullable|array',
      'tags.*' => 'string|max:50',
      'service_ids' => 'nullable|array',
      'service_ids.*' => 'exists:services,id',
      'skill_ids' => 'nullable|array',
      'skill_ids.*' => 'exists:skills,id',
    ]);

    $data = $request->except('thumbnail', 'service_ids', 'skill_ids');

    // Handle thumbnail upload
    if ($request->hasFile('thumbnail')) {
      $path = $request->file('thumbnail')->store('projects', 'public');
      $data['thumbnail_url'] = Storage::url($path);
    }

    // Ensure arrays are not null
    $data['technologies_en'] = $data['technologies_en'] ?? [];
    $data['technologies_ar'] = $data['technologies_ar'] ?? [];
    $data['tags'] = $data['tags'] ?? [];
    $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
    $data['is_active'] = (bool) ($data['is_active'] ?? true);
    $data['sort_order'] = $data['sort_order'] ?? 0;

    $project = Project::create($data);

    // Sync relationships
    if ($request->filled('service_ids')) {
      $project->services()->sync($request->service_ids);
    }
    if ($request->filled('skill_ids')) {
      $project->skills()->sync($request->skill_ids);
    }

    return to_route('admin.projects.index')->with('success', 'Project created successfully.');
  }

  /**
   * Show the form for editing the specified project.
   */
  public function edit(Project $project)
  {
    $project->load(['services:id,title,slug', 'skills:id,name,slug']);

    $services = Service::active()->get(['id', 'title', 'slug']);
    $skills = Skill::active()->get(['id', 'name', 'slug']);

    return Inertia::render('Dashboard/Projects/Edit', [
      'project' => $project,
      'services' => $services,
      'skills' => $skills,
    ]);
  }

  /**
   * Update the specified project in storage.
   */
  public function update(Request $request, Project $project)
  {
    $request->validate([
      'title_en' => 'required|string|max:255',
      'title_ar' => 'nullable|string|max:255',
      'description_en' => 'required|string',
      'description_ar' => 'nullable|string',
      'content_en' => 'nullable|string',
      'content_ar' => 'nullable|string',
      'category' => 'required|string|max:100',
      'is_featured' => 'boolean',
      'is_active' => 'boolean',
      'sort_order' => 'integer|min:0',
      'completed_at' => 'nullable|date',
      'technologies_en' => 'nullable|array',
      'technologies_en.*' => 'string|max:100',
      'technologies_ar' => 'nullable|array',
      'technologies_ar.*' => 'string|max:100',
      'github_url' => 'nullable|url',
      'live_url' => 'nullable|url',
      'demo_url' => 'nullable|url',
      'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      'tags' => 'nullable|array',
      'tags.*' => 'string|max:50',
      'service_ids' => 'nullable|array',
      'service_ids.*' => 'exists:services,id',
      'skill_ids' => 'nullable|array',
      'skill_ids.*' => 'exists:skills,id',
    ]);

    $data = $request->except('thumbnail', 'service_ids', 'skill_ids');

    // Handle thumbnail
    if ($request->hasFile('thumbnail')) {
      // Delete old thumbnail if exists and not default
      if ($project->thumbnail_url && strpos($project->thumbnail_url, '/storage/') === 0) {
        $oldPath = basename(parse_url($project->thumbnail_url, PHP_URL_PATH));
        Storage::disk('public')->delete('projects/' . $oldPath);
      }

      $path = $request->file('thumbnail')->store('projects', 'public');
      $data['thumbnail_url'] = Storage::url($path);
    }

    $data['technologies_en'] = $data['technologies_en'] ?? [];
    $data['technologies_ar'] = $data['technologies_ar'] ?? [];
    $data['tags'] = $data['tags'] ?? [];
    $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
    $data['is_active'] = (bool) ($data['is_active'] ?? true);
    $data['sort_order'] = $data['sort_order'] ?? 0;

    $project->update($data);

    // Sync relationships
    $project->services()->sync($request->service_ids ?? []);
    $project->skills()->sync($request->skill_ids ?? []);

    return to_route('admin.projects.index')->with('success', 'Project updated successfully.');
  }

  /**
   * Remove the specified project from storage.
   */
  public function destroy(Project $project)
  {
    $project->delete();
    return to_route('admin.projects.index')->with('success', 'Project deleted successfully.');
  }
}
