<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use App\Models\Service;
use App\Models\PortfolioContent;
use App\Http\Requests\ServiceStoreRequest;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServiceCollection;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|Inertia
    {
        $services = Service::getActiveServices()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('title_en', 'like', "%{$search}%")
                      ->orWhere('title_ar', 'like', "%{$search}%")
                      ->orWhere('description_en', 'like', "%{$search}%")
                      ->orWhere('description_ar', 'like', "%{$search}%");
            })
            ->get();

        // Return JSON for API requests, Inertia for web requests
        if ($request->wantsJson()) {
            return new JsonResponse(new ServiceCollection($services));
        }

        return Inertia::render('Admin/Services/Index', [
            'services' => new ServiceCollection($services),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Inertia
    {
        return Inertia::render('Admin/Services/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        $service = Service::create($validated);

        return new JsonResponse(new ServiceResource($service), 201);
    }

    /**
     * Get basic portfolio data for layout.
     */
    private function getPortfolioData(): array
    {
        $personalInfo = PortfolioContent::active()->byType('personal')->first();
        
        return [
            'name' => $personalInfo?->getLocalizedContent('title') ?? 'Ziad Hassan',
            'title' => $personalInfo?->getLocalizedContent('subtitle') ?? 'Software Engineer',
            'github' => $personalInfo?->getLocalizedContent('github') ?? 'https://github.com/Ziad-Abaza',
            'linkedin' => $personalInfo?->getLocalizedContent('linkedin') ?? 'https://www.linkedin.com/in/ziad-h-abaza-82276331b',
            'instagram' => $personalInfo?->getLocalizedContent('instagram') ?? 'https://instagram.com/3_p0ox',
            'email' => $personalInfo?->getLocalizedContent('email') ?? 'zeyad.h.abaza@gmail.com',
            'phone' => $personalInfo?->getLocalizedContent('phone') ?? '+20 100 640 3927',
            'location' => $personalInfo?->getLocalizedContent('location') ?? 'Alexandria, Egypt',
        ];
    }

    /**
     * Display the specified resource for public viewing.
     */
    public function showPublic(string $slug): \Inertia\Response
    {
        $service = Service::getActiveServices()->bySlug($slug)->firstOrFail();
        $service->load(['projects' => function($query) {
            $query->active()->latest('projects.created_at')->take(6);
        }, 'skills' => function($query) {
            $query->active()->ordered();
        }]);

        // Get related services
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
                'technologies' => $service->technologies_list,
                'features' => $service->features,
                'image_url' => $service->image_url,
                'sort_order' => $service->sort_order,
                'projects' => $service->projects->map(function ($project) {
                    return [
                        'id' => $project->id,
                        'title' => $project->title,
                        'description' => $project->description,
                        'technologies' => $project->technologies_list,
                        'category' => $project->category,
                        'thumbnail_url' => $project->thumbnail_url,
                        'github_url' => $project->github_url,
                        'live_url' => $project->live_url,
                    ];
                }),
                'skills' => $service->skills->map(function ($skill) {
                    return [
                        'name' => $skill->name,
                        'category' => $skill->category,
                        'proficiency_level' => $skill->proficiency_level,
                    ];
                }),
            ],
            'relatedServices' => $relatedServices->map(function ($relatedService) {
                return [
                    'id' => $relatedService->id,
                    'slug' => $relatedService->slug,
                    'icon' => $relatedService->icon,
                    'title' => $relatedService->title,
                    'description' => $relatedService->description,
                    'image_url' => $relatedService->image_url,
                    'technologies' => $relatedService->technologies_list,
                ];
            }),
            'translations' => $this->getServiceTranslations(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service): JsonResponse|Inertia
    {
        $service->load(['projects', 'skills']);

        if (request()->wantsJson()) {
            return new JsonResponse(new ServiceResource($service));
        }

        return Inertia::render('Admin/Services/Show', [
            'service' => new ServiceResource($service),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service): Inertia
    {
        return Inertia::render('Admin/Services/Edit', [
            'service' => new ServiceResource($service),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceStoreRequest $request, Service $service): JsonResponse
    {
        $validated = $request->validated();
        
        $service->update($validated);

        return new JsonResponse(new ServiceResource($service));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service): JsonResponse
    {
        $service->delete();

        return new JsonResponse(null, 204);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Service $service): JsonResponse
    {
        $service->restore();

        return new JsonResponse(new ServiceResource($service));
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete(Service $service): JsonResponse
    {
        $service->forceDelete();

        return new JsonResponse(null, 204);
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggle(Service $service): JsonResponse
    {
        $service->update(['is_active' => !$service->is_active]);

        return new JsonResponse(new ServiceResource($service));
    }

    /**
     * Get services for public display (API endpoint).
     */
    public function public(): JsonResponse
    {
        $services = Service::getActiveServices()->get();

        return new JsonResponse(new ServiceCollection($services));
    }

    /**
     * Get translations for service detail page.
     */
    private function getServiceTranslations(): array
    {
        return __('portfolio');
    }
}
