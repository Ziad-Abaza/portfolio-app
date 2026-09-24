/**
 * Public site entry — boots the System Field and the motion layer.
 * Ships as a small module bundle; all content is server-rendered HTML.
 */

import '../css/app.css';
import { SystemField } from './engine/field';
import { initMotion } from './engine/motion';

const fx = (window as any).__FX__ ?? { field: { enabled: true, density: 1, intensity: 1 } };

let field: SystemField | null = null;
const canvas = document.getElementById('system-field') as HTMLCanvasElement | null;
if (canvas) {
    try {
        field = new SystemField(canvas, fx.field ?? { enabled: true, density: 1, intensity: 1 });
        field.start();
    } catch {
        // Canvas unsupported — the site still works; field is enhancement.
        canvas.remove();
    }
}

initMotion(field);
