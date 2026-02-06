<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;

class ContactController extends AbstractPortfolioController
{
  public function showForm()
  {
    return Inertia::render('Portfolio/Contact', [
      'data' => $this->getPortfolioData(),
      'translations' => $this->translations(),
    ]);
  }

  public function submit(ContactFormRequest $request): RedirectResponse
  {
    // ContactSubmission::create($request->validated());

    return back()->with('success', __('portfolio.contact_success'));
  }
}
