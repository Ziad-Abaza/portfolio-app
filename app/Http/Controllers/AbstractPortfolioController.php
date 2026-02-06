<?php

namespace App\Http\Controllers;

use App\Models\PortfolioContent;
use App\Models\Service;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Statistic;

abstract class AbstractPortfolioController extends Controller
{
  protected function getPortfolioData(): array
  {
    $personalInfo = PortfolioContent::active()->byType('personal')->first();
    $services = Service::getActiveServices()->ordered()->get();
    $projects = Project::getActiveProjects()->latest()->get();
    $skills = Skill::getActiveSkills()->ordered()->get();
    $statistics = Statistic::getActiveStatistics()->ordered()->get();

    return [
      'name' => $personalInfo?->getLocalizedContent('title') ?? 'Ziad Hassan',
      'title' => $personalInfo?->getLocalizedContent('subtitle') ?? 'Software Engineer',
      'summary' => $personalInfo?->getLocalizedContent('content') ?? 'Software Engineer specializing in building scalable and efficient software solutions.',
      'location' => $personalInfo?->getLocalizedContent('location') ?? 'Alexandria, Egypt',
      'email' => $personalInfo?->getLocalizedContent('email') ?? 'zeyad.h.abaza@gmail.com',
      'phone' => $personalInfo?->getLocalizedContent('phone') ?? '+20 100 640 3927',
      'github' => trim($personalInfo?->getLocalizedContent('github') ?? 'https://github.com/Ziad-Abaza'),
      'linkedin' => trim($personalInfo?->getLocalizedContent('linkedin') ?? 'https://www.linkedin.com/in/ziad-h-abaza-82276331b'),
      'instagram' => trim($personalInfo?->getLocalizedContent('instagram') ?? 'https://instagram.com/3_p0ox'),
      'portfolio_website' => trim($personalInfo?->getLocalizedContent('portfolio_website') ?? 'https://ziad-abaza.github.io/Portfolio/index.html'),

      'expertise_areas' => $this->getExpertiseAreas(),

      'services' => $services->map(fn($s) => [
        'id' => $s->id,
        'slug' => $s->slug,
        'icon' => $s->icon,
        'title' => $s->title,
        'description' => $s->description,
        'technologies' => $s->technologies_list,
        'features' => $s->features,
        'image_url' => $s->image_url,
      ])->values(),

      'projects' => $projects->map(fn($p) => [
        'id' => $p->id,
        'title' => $p->title,
        'description' => $p->description,
        'category' => $p->category,
        'technologies' => $p->technologies_list ?? [],
        'created_at' => $p->created_at?->toDateTimeString() ?? now()->toDateTimeString(),
        'thumbnail_url' => $p->thumbnail_url,
        'github_url' => $p->github_url,
        'live_url' => $p->live_url,
        'is_featured' => $p->is_featured,
      ])->values(),

      'technical_skills' => $skills->groupBy('category')->map(fn($group) => $group->pluck('name')->toArray()),

      'statistics' => $statistics->mapWithKeys(fn($s) => [$s->key => $s->value]),

      'academic_background' => PortfolioContent::active()->byKey('academic_background')->first()?->getLocalizedContent('content')
        ?? 'Studying Information Technology with a focus on Artificial Intelligence and Smart City technologies.',

      'testimonials' => PortfolioContent::active()->byType('testimonial')->get()->map(fn($t) => [
        'name' => $t->getLocalizedContent('title'),
        'position' => $t->getLocalizedContent('subtitle'),
        'content' => $t->getLocalizedContent('content'),
        'rating' => $t->metadata['rating'] ?? 5,
      ])->values(),

      'recent_posts' => PortfolioContent::active()->byType('blog_post')->latest()->take(3)->get()->map(fn($p) => [
        'title' => $p->getLocalizedContent('title'),
        'date' => $p->created_at?->format('Y-m-d') ?? now()->format('Y-m-d'),
        'excerpt' => $p->getLocalizedContent('description'),
        'category' => $p->getLocalizedContent('category'),
      ])->values(),
    ];
  }

  private function getExpertiseAreas(): array
  {
    $expertiseContent = PortfolioContent::active()->byKey('expertise_areas')->first();
    if ($expertiseContent) {
      $data = $expertiseContent->getLocalizedContent('content');
      if (is_array($data)) return $data;
    }

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

  protected function translations(): array
  {
    return __('portfolio');
  }
}
