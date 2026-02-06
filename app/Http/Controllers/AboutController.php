<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class AboutController extends AbstractPortfolioController
{
  public function show()
  {
    return Inertia::render('Portfolio/About', [
      'data' => $this->getPortfolioData(),
      'translations' => $this->translations(),
    ]);
  }
}
