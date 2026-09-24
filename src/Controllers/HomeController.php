<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Metric;
use App\Models\Project;
use App\Models\Section;
use App\Models\SeoMeta;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\TimelineEntry;

final class HomeController
{
    public function index(Request $req): Response
    {
        return Response::view('pages/home', [
            'sections' => Section::visibleMap(),
            'projects' => Project::published(),
            'skills' => Skill::grouped(),
            'timeline' => TimelineEntry::all('sort_order'),
            'metrics' => Metric::all('sort_order'),
            'socials' => SocialLink::visible(),
            'seo' => SeoMeta::forPage('home'),
            'page' => 'home',
        ]);
    }
}
