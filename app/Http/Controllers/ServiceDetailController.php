<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ServiceDetailController extends AbstractPortfolioController
{
  public function show(string $slug)
  {
    $service = Service::where('slug', $slug)->firstOrFail();

    return Inertia::render('Portfolio/ServiceDetail', [
      'service' => [
        'id' => $service->id,
        'slug' => $service->slug,
        'title' => $service->title,
        'description' => $service->description,
        'technologies' => $service->technologies_list,
        'features' => $service->features,
        'image_url' => $service->image_url,
      ],
      'data' => $this->getPortfolioData(), 
      'translations' => $this->translations(),
    ]);
  }
}
