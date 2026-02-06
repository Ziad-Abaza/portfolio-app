<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class SkillsController extends AbstractPortfolioController
{
  public function show()
  {
    return Inertia::render('Portfolio/Skills', [
      'data' => $this->getPortfolioData(),
      'translations' => $this->translations(),
    ]);
  }
}
