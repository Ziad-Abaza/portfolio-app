<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\DB;
use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Models\Project;

final class ProjectApiController
{
    private const FIELDS = [
        'slug', 'title', 'summary', 'body', 'role', 'domain', 'stack',
        'links', 'cover', 'metrics', 'featured', 'status', 'sort_order', 'published_at',
    ];

    private const BLOCK_TYPES = ['problem', 'approach', 'architecture', 'outcome', 'metrics', 'text', 'gallery'];

    public function index(Request $req): Response
    {
        return Response::json(['data' => Project::all('sort_order')]);
    }

    public function show(Request $req): Response
    {
        $project = Project::find((int) $req->param('id'));
        if ($project === null) {
            return Response::json(['error' => 'Not found'], 404);
        }
        $project['blocks'] = Project::blocks((int) $project['id']);
        return Response::json(['data' => $project]);
    }

    public function store(Request $req): Response
    {
        $validator = Validator::make($req->body, [
            'slug' => 'required|slug|max:190',
            'title' => 'required|array',
            'status' => 'nullable|in:draft,published',
        ]);
        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }
        if (DB::fetch('SELECT id FROM projects WHERE slug = ?', [$req->body['slug']]) !== null) {
            return Response::json(['errors' => ['slug' => ['taken']]], 422);
        }
        $data = $this->payload($req);
        $data['published_at'] ??= $data['status'] === 'published' ? date('Y-m-d H:i:s') : null;
        $id = Project::create($data);
        return Response::json(['data' => Project::find($id)], 201);
    }

    public function update(Request $req): Response
    {
        $id = (int) $req->param('id');
        $existing = Project::find($id);
        if ($existing === null) {
            return Response::json(['error' => 'Not found'], 404);
        }
        $validator = Validator::make($req->body, [
            'slug' => 'nullable|slug|max:190',
            'status' => 'nullable|in:draft,published',
        ]);
        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }
        if (isset($req->body['slug']) && $req->body['slug'] !== $existing['slug']) {
            if (DB::fetch('SELECT id FROM projects WHERE slug = ? AND id != ?', [$req->body['slug'], $id]) !== null) {
                return Response::json(['errors' => ['slug' => ['taken']]], 422);
            }
        }
        $data = $this->payload($req);
        if (($data['status'] ?? null) === 'published' && empty($existing['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        Project::update($id, $data);
        return Response::json(['data' => Project::find($id)]);
    }

    public function destroy(Request $req): Response
    {
        Project::delete((int) $req->param('id'));
        return Response::json(['ok' => true]);
    }

    public function storeBlock(Request $req): Response
    {
        $project = Project::find((int) $req->param('id'));
        if ($project === null) {
            return Response::json(['error' => 'Not found'], 404);
        }
        $type = (string) ($req->body['type'] ?? 'text');
        if (!in_array($type, self::BLOCK_TYPES, true)) {
            return Response::json(['errors' => ['type' => ['invalid']]], 422);
        }
        $id = DB::insert('case_study_blocks', [
            'project_id' => (int) $project['id'],
            'type' => $type,
            'content' => json_encode($req->body['content'] ?? [], JSON_UNESCAPED_UNICODE),
            'sort_order' => (int) ($req->body['sort_order'] ?? 0),
        ]);
        return Response::json(['data' => DB::fetch('SELECT * FROM case_study_blocks WHERE id = ?', [$id])], 201);
    }

    public function updateBlock(Request $req): Response
    {
        $id = (int) $req->param('id');
        $data = [];
        if (isset($req->body['type']) && in_array($req->body['type'], self::BLOCK_TYPES, true)) {
            $data['type'] = $req->body['type'];
        }
        if (array_key_exists('content', $req->body)) {
            $data['content'] = json_encode($req->body['content'], JSON_UNESCAPED_UNICODE);
        }
        if (isset($req->body['sort_order'])) {
            $data['sort_order'] = (int) $req->body['sort_order'];
        }
        if ($data !== []) {
            DB::update('case_study_blocks', $data, 'id = ?', [$id]);
        }
        return Response::json(['data' => DB::fetch('SELECT * FROM case_study_blocks WHERE id = ?', [$id])]);
    }

    public function destroyBlock(Request $req): Response
    {
        DB::delete('case_study_blocks', 'id = ?', [(int) $req->param('id')]);
        return Response::json(['ok' => true]);
    }

    private function payload(Request $req): array
    {
        $data = [];
        foreach (self::FIELDS as $field) {
            if (!array_key_exists($field, $req->body)) {
                continue;
            }
            $value = $req->body[$field];
            if (in_array($field, ['featured'], true)) {
                $value = (int) (bool) $value;
            } elseif ($field === 'sort_order') {
                $value = (int) $value;
            }
            $data[$field] = $value;
        }
        return $data;
    }
}
