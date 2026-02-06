<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ProjectsController extends AbstractPortfolioController
{
  public function show()
  {
    $data = $this->getPortfolioData();

    if (isset($data['projects'])) {
      $data['projects'] = collect($data['projects'])->map(function ($project) {
        return array_merge($project, [
          'technologies' => is_array($project['technologies'] ?? null) ? $project['technologies'] : [],
          'category' => $project['category'] ?? 'uncategorized',
          'created_at' => $project['created_at'] ?? now()->toDateTimeString(),
        ]);
      })->all();
    }

    return Inertia::render('Portfolio/Projects', [
      'data' => $data,
      'translations' => $this->translations(),
    ]);
  }
}
