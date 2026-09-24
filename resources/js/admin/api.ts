/** Admin API client — session auth + CSRF header on mutations. */

const boot = (window as any).__ADMIN__ ?? {};

export const adminBoot = {
    csrf: (boot.csrf as string) ?? '',
    user: (boot.user as { name?: string; email?: string } | null) ?? null,
    unread: (boot.unread as number) ?? 0,
};

interface ApiOptions {
    method?: string;
    body?: unknown;
    form?: FormData;
}

export async function api<T = any>(path: string, opts: ApiOptions = {}): Promise<T> {
    const headers: Record<string, string> = {
        'Accept': 'application/json',
        'X-CSRF-Token': adminBoot.csrf,
    };
    let body: BodyInit | undefined;
    if (opts.form) {
        body = opts.form;
    } else if (opts.body !== undefined) {
        headers['Content-Type'] = 'application/json';
        body = JSON.stringify(opts.body);
    }

    const res = await fetch(`/api/admin${path}`, {
        method: opts.method ?? (body !== undefined ? 'POST' : 'GET'),
        headers,
        body,
        credentials: 'same-origin',
    });

    if (res.status === 401) {
        window.location.href = '/admin/login';
        throw new Error('unauthenticated');
    }
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
        throw Object.assign(new Error((data as any).error ?? `HTTP ${res.status}`), { payload: data, status: res.status });
    }
    return data as T;
}

/** Convenience wrappers */
export const get = <T = any>(path: string) => api<T>(path);
export const post = <T = any>(path: string, body?: unknown) => api<T>(path, { method: 'POST', body });
export const put = <T = any>(path: string, body?: unknown) => api<T>(path, { method: 'PUT', body });
export const del = <T = any>(path: string) => api<T>(path, { method: 'DELETE' });
export const upload = <T = any>(path: string, form: FormData) => api<T>(path, { method: 'POST', form });
