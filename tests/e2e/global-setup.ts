import { execSync } from 'node:child_process';

/** Reset rate-limits + messages so E2E flows start from a clean state. */
export default function globalSetup(): void {
    execSync('php bin/test-reset.php', { stdio: 'inherit' });
}
