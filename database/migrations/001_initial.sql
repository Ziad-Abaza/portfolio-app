-- The Living System — initial schema (SQLite / MySQL-compatible types)

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS sections (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    key TEXT UNIQUE NOT NULL,
    name TEXT NOT NULL,                 -- JSON {"en":"..","ar":".."}
    sort_order INTEGER NOT NULL DEFAULT 0,
    visible INTEGER NOT NULL DEFAULT 1,
    props TEXT                          -- JSON per-section config
);

CREATE TABLE IF NOT EXISTS projects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT UNIQUE NOT NULL,
    title TEXT NOT NULL,                -- JSON {"en","ar"}
    summary TEXT,                       -- JSON
    body TEXT,                          -- JSON (markdown-ish rich text)
    role TEXT,                          -- JSON
    domain TEXT,                        -- JSON e.g. "SaaS · Multi-tenant"
    stack TEXT,                         -- JSON array
    links TEXT,                         -- JSON {"live":"","github":""}
    cover TEXT,                         -- JSON {"hue":..,"glyph":".."} or media path
    metrics TEXT,                       -- JSON array of {label:{en,ar},value}
    featured INTEGER NOT NULL DEFAULT 0,
    status TEXT NOT NULL DEFAULT 'draft',   -- draft | published
    sort_order INTEGER NOT NULL DEFAULT 0,
    published_at TEXT,
    created_at TEXT NOT NULL DEFAULT (datetime('now')),
    updated_at TEXT
);
CREATE INDEX IF NOT EXISTS idx_projects_status ON projects(status, featured, sort_order);

CREATE TABLE IF NOT EXISTS case_study_blocks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    project_id INTEGER NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
    type TEXT NOT NULL,                 -- problem|approach|architecture|outcome|metrics|text|gallery
    content TEXT NOT NULL,              -- JSON, shape depends on type
    sort_order INTEGER NOT NULL DEFAULT 0
);
CREATE INDEX IF NOT EXISTS idx_blocks_project ON case_study_blocks(project_id, sort_order);

CREATE TABLE IF NOT EXISTS skills (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    grp TEXT NOT NULL,                  -- JSON {"en","ar"} group label
    name TEXT NOT NULL,
    level INTEGER NOT NULL DEFAULT 50,  -- 0-100
    note TEXT,                          -- JSON
    sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS timeline_entries (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    year TEXT NOT NULL,
    title TEXT NOT NULL,                -- JSON
    description TEXT,                   -- JSON
    kind TEXT NOT NULL DEFAULT 'work',  -- work|oss|product|milestone
    sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS metrics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    label TEXT NOT NULL,                -- JSON
    value TEXT NOT NULL,
    suffix TEXT,
    context TEXT,                       -- JSON — where/why it matters
    sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS social_links (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    label TEXT NOT NULL,
    url TEXT NOT NULL,
    icon TEXT NOT NULL DEFAULT 'link',  -- github|linkedin|x|mail|link
    sort_order INTEGER NOT NULL DEFAULT 0,
    visible INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS themes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    tokens TEXT NOT NULL,               -- {"shared":{..},"dark":{..},"light":{..}}
    is_active INTEGER NOT NULL DEFAULT 0,
    is_builtin INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS settings (
    key TEXT PRIMARY KEY,
    value TEXT NOT NULL                 -- JSON-encoded
);

CREATE TABLE IF NOT EXISTS seo_meta (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    page TEXT UNIQUE NOT NULL,          -- home|work|contact|project:{slug}
    title TEXT,                         -- JSON
    description TEXT,                   -- JSON
    og_image TEXT
);

CREATE TABLE IF NOT EXISTS contact_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    message TEXT NOT NULL,
    locale TEXT NOT NULL DEFAULT 'en',
    ip_hash TEXT NOT NULL,
    read_at TEXT,
    created_at TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    filename TEXT NOT NULL,             -- random name on disk (storage/uploads)
    orig_name TEXT NOT NULL,
    mime TEXT NOT NULL,
    size INTEGER NOT NULL,
    alt TEXT,                           -- JSON
    created_at TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS rate_limits (
    key TEXT PRIMARY KEY,
    attempts INTEGER NOT NULL DEFAULT 0,
    reset_at INTEGER NOT NULL DEFAULT 0
);
