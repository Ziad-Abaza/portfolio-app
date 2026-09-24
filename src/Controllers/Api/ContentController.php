<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\DB;
use App\Core\Request;
use App\Core\Response;
use App\Models\Metric;
use App\Models\Section;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\TimelineEntry;

/**
 * Generic CRUD for the small content resources. Every resource declares a
 * strict field whitelist — no mass assignment of arbitrary columns.
 */
final class ContentController
{
    private const RESOURCES = [
        'sections' => [
            'model' => Section::class,
            'fields' => ['name', 'sort_order', 'visible', 'props'],
            'order' => 'sort_order',
        ],
        'skills' => [
            'model' => Skill::class,
            'fields' => ['grp', 'name', 'level', 'note', 'sort_order'],
            'order' => 'sort_order',
        ],
        'timeline' => [
            'model' => TimelineEntry::class,
            'fields' => ['year', 'title', 'description', 'kind', 'sort_order'],
            'order' => 'sort_order',
        ],
        'metrics' => [
            'model' => Metric::class,
            'fields' => ['label', 'value', 'suffix', 'context', 'sort_order'],
            'order' => 'sort_order',
        ],
        'socials' => [
            'model' => SocialLink::class,
            'fields' => ['label', 'url', 'icon', 'sort_order', 'visible'],
            'order' => 'sort_order',
        ],
    ];

    private const BOOL_FIELDS = ['visible', 'featured'];
    private const INT_FIELDS = ['sort_order', 'level'];

    public function index(Request $req): Response
    {
        $resource = $this->resource($req);
        if ($resource === null) {
            return Response::json(['error' => 'Unknown resource'], 404);
        }
        return Response::json(['data' => $resource['model']::all($resource['order'])]);
    }

    public function store(Request $req): Response
    {
        $resource = $this->resource($req);
        if ($resource === null) {
            return Response::json(['error' => 'Unknown resource'], 404);
        }
        $id = $resource['model']::create($this->payload($req, $resource['fields']));
        return Response::json(['data' => $resource['model']::find($id)], 201);
    }

    public function update(Request $req): Response
    {
        $resource = $this->resource($req);
        if ($resource === null) {
            return Response::json(['error' => 'Unknown resource'], 404);
        }
        $id = (int) $req->param('id');
        if ($resource['model']::find($id) === null) {
            return Response::json(['error' => 'Not found'], 404);
        }
        $resource['model']::update($id, $this->payload($req, $resource['fields']));
        return Response::json(['data' => $resource['model']::find($id)]);
    }

    public function destroy(Request $req): Response
    {
        $resource = $this->resource($req);
        if ($resource === null) {
            return Response::json(['error' => 'Unknown resource'], 404);
        }
        $resource['model']::delete((int) $req->param('id'));
        return Response::json(['ok' => true]);
    }

    public function reorder(Request $req): Response
    {
        $resource = $this->resource($req);
        if ($resource === null) {
            return Response::json(['error' => 'Unknown resource'], 404);
        }
        $order = $req->body['order'] ?? [];
        if (!is_array($order)) {
            return Response::json(['error' => 'order must be an array of ids'], 422);
        }
        $table = $this->tableName($resource['model']);
        DB::transaction(function () use ($order, $table): void {
            foreach (array_values($order) as $position => $id) {
                DB::update($table, ['sort_order' => $position], 'id = ?', [(int) $id]);
            }
        });
        return Response::json(['ok' => true]);
    }

    private function resource(Request $req): ?array
    {
        $key = (string) $req->param('resource');
        return self::RESOURCES[$key] ?? null;
    }

    /** @return class-string */
    private function tableName(string $model): string
    {
        // Read the protected static $table via reflection (kept internal).
        $prop = new \ReflectionProperty($model, 'table');
        return (string) $prop->getValue();
    }

    private function payload(Request $req, array $fields): array
    {
        $data = [];
        foreach ($fields as $field) {
            if (!array_key_exists($field, $req->body)) {
                continue;
            }
            $value = $req->body[$field];
            if (in_array($field, self::BOOL_FIELDS, true)) {
                $value = (int) (bool) $value;
            } elseif (in_array($field, self::INT_FIELDS, true)) {
                $value = (int) $value;
            }
            $data[$field] = $value;
        }
        return $data;
    }
}
