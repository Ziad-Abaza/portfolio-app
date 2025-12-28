<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\ContactFormRequest;
use App\Models\Service;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Statistic;
use App\Models\PortfolioContent;

class PortfolioController extends Controller
{
    /**
     * Get expertise areas from PortfolioContent or fallback to hardcoded values
     */
    private function getExpertiseAreas(): array
    {
        // Try to get expertise areas from PortfolioContent
        $expertiseContent = PortfolioContent::active()->byKey('expertise_areas')->first();
        
        if ($expertiseContent) {
            $expertiseData = $expertiseContent->getLocalizedContent('content');
            if (is_array($expertiseData)) {
                return $expertiseData;
            }
        }
        
        // Fallback to hardcoded expertise areas
        return [
            'AI & Machine Learning',
            'Web Development (Frontend & Backend)',
            'Embedded Systems & IoT',
            'Database Design',
            'Data Analysis & NLP',
            'DevOps & CI/CD',
            'API Integration',
            'Algorithms and Problem Solving'
        ];
    }

    /**
     * Get dynamic portfolio data from models
     */
    private function getPortfolioData(): array
    {
        // Get personal info from PortfolioContent
        $personalInfo = PortfolioContent::active()->byType('personal')->first();
        
        // Get services
        $services = Service::getActiveServices()->ordered()->get();
        
        // Get projects
        $projects = Project::getActiveProjects()->latest()->get();
        
        // Get skills
        $skills = Skill::getActiveSkills()->ordered()->get();
        
        // Get statistics
        $statistics = Statistic::getActiveStatistics()->ordered()->get();
        
        return [
            'name' => $personalInfo?->getLocalizedContent('title') ?? 'Ziad Hassan',
            'title' => $personalInfo?->getLocalizedContent('subtitle') ?? 'Software Engineer',
            'summary' => $personalInfo?->getLocalizedContent('content') ?? 'Software Engineer specializing in building scalable and efficient software solutions.',
            'location' => $personalInfo?->getLocalizedContent('location') ?? 'Alexandria, Egypt',
            'email' => $personalInfo?->getLocalizedContent('email') ?? 'zeyad.h.abaza@gmail.com',
            'phone' => $personalInfo?->getLocalizedContent('phone') ?? '+20 100 640 3927',
            'github' => $personalInfo?->getLocalizedContent('github') ?? 'https://github.com/Ziad-Abaza',
            'linkedin' => $personalInfo?->getLocalizedContent('linkedin') ?? 'https://www.linkedin.com/in/ziad-h-abaza-82276331b',
            'instagram' => $personalInfo?->getLocalizedContent('instagram') ?? 'https://instagram.com/3_p0ox',
            'portfolio_website' => $personalInfo?->getLocalizedContent('portfolio_website') ?? 'https://ziad-abaza.github.io/Portfolio/index.html',
            
            // Expertise areas (from PortfolioContent or hardcoded fallback)
            'expertise_areas' => $this->getExpertiseAreas(),
            
            // Dynamic services from model
            'services' => $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'slug' => $service->slug,
                    'icon' => $service->icon,
                    'title' => $service->title,
                    'description' => $service->description,
                    'technologies' => $service->technologies_list,
                    'features' => $service->features,
                    'image_url' => $service->image_url,
                    'sort_order' => $service->sort_order,
                ];
            }),
            
            // Dynamic projects from model
            'projects' => $projects->map(function ($project) {
                // Ensure technologies is always an array and handle localization
                $technologies = is_array($project->technologies) ? $project->technologies : [];
                
                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'description' => $project->description,
                    'category' => $project->category,
                    'technologies' => $technologies,
                    'created_at' => $project->created_at ? $project->created_at->toDateTimeString() : now()->toDateTimeString(),
                    'thumbnail_url' => $project->thumbnail_url,
                    'technologies' => $project->technologies_list,
                    'category' => $project->category,
                    'github_url' => $project->github_url,  // Changed from github_link
                    'live_url' => $project->live_url,      // Changed from live_link
                    'thumbnail_url' => $project->thumbnail_url,
                    'is_featured' => $project->is_featured,
                ];
            }),
            
            // Dynamic skills from model
            'technical_skills' => $skills->groupBy('category')->map(function ($categorySkills, $category) {
                return $categorySkills->map(function ($skill) {
                    return $skill->name;
                })->toArray();
            }),
            
            // Dynamic statistics from model
            'statistics' => $statistics->mapWithKeys(function ($statistic) {
                return [$statistic->key => $statistic->value];
            }),
            
            // Academic background from PortfolioContent
            'academic_background' => PortfolioContent::active()->byKey('academic_background')->first()?->getLocalizedContent('content') 
                ?? 'Studying Information Technology with a focus on Artificial Intelligence and Smart City technologies.',
            
            // Testimonials from PortfolioContent (or could be a separate model)
            'testimonials' => PortfolioContent::active()->byType('testimonial')->get()->map(function ($testimonial) {
                return [
                    'name' => $testimonial->getLocalizedContent('title'),
                    'position' => $testimonial->getLocalizedContent('subtitle'),
                    'content' => $testimonial->getLocalizedContent('content'),
                    'rating' => $testimonial->metadata['rating'] ?? 5,
                ];
            }),
            
            // Recent posts from PortfolioContent
            'recent_posts' => PortfolioContent::active()->byType('blog_post')->latest()->take(3)->get()->map(function ($post) {
                return [
                    'title' => $post->getLocalizedContent('title'),
                    'date' => $post->created_at ? $post->created_at->format('Y-m-d') : now()->format('Y-m-d'),
                    'excerpt' => $post->getLocalizedContent('description'),
                    'category' => $post->getLocalizedContent('category'),
                ];
            }),
        ];
    }

    /**
     * Home page
     */
    public function home()
    {
        return Inertia::render('Portfolio/Home', [
            'data' => $this->getPortfolioData(),
            'translations' => $this->getTranslations()
        ]);
    }

    /**
     * About page
     */
    public function about()
    {
        return Inertia::render('Portfolio/About', [
            'data' => $this->getPortfolioData(),
            'translations' => $this->getTranslations()
        ]);
    }

    /**
     * Skills page
     */
    public function skills()
    {
        return Inertia::render('Portfolio/Skills', [
            'data' => $this->getPortfolioData(),
            'translations' => $this->getTranslations()
        ]);
    }

    /**
     * Projects page
     */
    public function projects()
    {
        $data = $this->getPortfolioData();
        
        // Ensure projects data is properly structured
        if (isset($data['projects'])) {
            $data['projects'] = collect($data['projects'])->map(function ($project) {
                return array_merge($project, [
                    'technologies' => is_array($project['technologies'] ?? null) 
                        ? $project['technologies'] 
                        : [],
                    'category' => $project['category'] ?? 'uncategorized',
                    'created_at' => $project['created_at'] ?? now()->toDateTimeString(),
                ]);
            })->toArray();
        }
        
        return Inertia::render('Portfolio/Projects', [
            'data' => $data,
            'translations' => $this->getTranslations()
        ]);
    }

    /**
     * Contact page
     */
    public function contact()
    {
        return Inertia::render('Portfolio/Contact', [
            'data' => $this->getPortfolioData(),
            'translations' => $this->getTranslations()
        ]);
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(ContactFormRequest $request)
    {
        // The request is already validated by ContactFormRequest
        $validated = $request->validated();

        // Here you would typically send an email or store in database
        // For now, we'll just redirect back with success message
        
        return redirect()->route('portfolio.contact')
            ->with('success', __('portfolio.contact_success'));
    }

    /**
     * Get translations for the current locale
     */
    private function getTranslations()
    {
        return __('portfolio');
    }
}
