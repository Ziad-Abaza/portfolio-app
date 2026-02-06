<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PortfolioContent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the portfolio items.
     */
    public function index(Request $request)
    {
        $query = PortfolioContent::query();

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                  ->orWhere('title_ar', 'like', "%{$search}%")
                  ->orWhere('content_en', 'like', "%{$search}%")
                  ->orWhere('content_ar', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $portfolioItems = $query->active()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $types = PortfolioContent::distinct()->pluck('type')->filter();

        return Inertia::render('Dashboard/Portfolio/Index', [
            'portfolioItems' => $portfolioItems,
            'filters' => $request->only(['search', 'type']),
            'types' => $types,
        ]);
    }

    /**
     * Show the form for creating a new portfolio item.
     */
    public function create()
    {
        return Inertia::render('Dashboard/Portfolio/Create', [
            'types' => ['service', 'skill', 'testimonial', 'about', 'contact_message'],
        ]);
    }

    /**
     * Store a newly created portfolio item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:service,skill,testimonial,about,contact_message'],
            'title_en' => ['required', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'content_en' => ['required', 'string'],
            'content_ar' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        PortfolioContent::create($validated);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio item created successfully.');
    }

    /**
     * Show the form for editing the specified portfolio item.
     */
    public function edit(PortfolioContent $portfolio)
    {
        return Inertia::render('Dashboard/Portfolio/Edit', [
            'portfolio' => $portfolio,
            'types' => ['service', 'skill', 'testimonial', 'about', 'contact_message'],
        ]);
    }

    /**
     * Update the specified portfolio item in storage.
     */
    public function update(Request $request, PortfolioContent $portfolio)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:service,skill,testimonial,about,contact_message'],
            'title_en' => ['required', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'content_en' => ['required', 'string'],
            'content_ar' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $portfolio->update($validated);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio item updated successfully.');
    }

    /**
     * Remove the specified portfolio item from storage.
     */
    public function destroy(PortfolioContent $portfolio)
    {
        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio item deleted successfully.');
    }
}
