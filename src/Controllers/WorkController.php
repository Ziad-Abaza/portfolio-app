<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Project;
use App\Models\SeoMeta;
use App\Models\SocialLink;

final class WorkController
{
    public function index(Request $req): Response
    {
        return Response::view('pages/work-index', [
            'projects' => Project::published(),
            'socials' => SocialLink::visible(),
            'seo' => SeoMeta::forPage('work'),
            'page' => 'work',
        ]);
    }

    public function show(Request $req): Response
    {
        $project = Project::findBySlug((string) $req->param('slug'));
        if ($project === null) {
            return Response::view('errors.404', [], 404);
        }
        return Response::view('pages/work-show', [
            'project' => $project,
            'blocks' => Project::blocks((int) $project['id']),
            'socials' => SocialLink::visible(),
            'seo' => SeoMeta::forPage('project:' . $project['slug']),
            'page' => 'work',
        ]);
    }
}
