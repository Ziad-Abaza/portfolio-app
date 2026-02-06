<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class HomeController extends AbstractPortfolioController
{
  public function show()
  {
    return Inertia::render('Portfolio/Home', [
      'data' => $this->getPortfolioData(),
      'translations' => $this->translations(),
    ]);
  }
}
