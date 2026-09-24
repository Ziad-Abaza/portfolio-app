<?php

/**
 * Downloads self-hosted woff2 fonts and generates resources/css/fonts.css.
 * Run once: php bin/fonts.php
 */

$outDir = dirname(__DIR__) . '/public/fonts';
$cssOut = dirname(__DIR__) . '/resources/css/fonts.css';
@mkdir($outDir, 0775, true);

$ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0 Safari/537.36';

function fetch(string $url): string
{
    global $ua;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => $ua,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CAINFO => dirname(__DIR__) . '/bin/cacert.pem',
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($body === false || $code >= 400) {
        throw new RuntimeException("fetch failed {$code}: {$url}");
    }
    return $body;
}

$urls = [
    // Google Fonts — one css2 call per family keeps unicode-range subsets intact
    'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap',
    'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;700&display=swap',
    'https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&display=swap',
    // Fontshare — Clash Display
    'https://api.fontshare.com/v2/css?f[]=clash-display@400,500,600,700&display=swap',
];

$cssAll = '';
$count = 0;
foreach ($urls as $cssUrl) {
    $css = fetch($cssUrl);
    // Download every woff2 — absolute https:// or protocol-relative //cdn… (fontshare)
    $css = preg_replace_callback(
        '/url\([\'"]?((?:https:)?\/\/[^)\'"]+\.woff2)[\'"]?\)/',
        function (array $m) use ($outDir, &$count): string {
            $url = str_starts_with($m[1], '//') ? 'https:' . $m[1] : $m[1];
            $name = substr(sha1($url), 0, 12) . '.woff2';
            $dest = $outDir . '/' . $name;
            if (!is_file($dest)) {
                file_put_contents($dest, fetch($url));
            }
            $count++;
            return "url('/fonts/{$name}')";
        },
        $css
    );
    // Drop remaining remote fallbacks (woff/ttf) — local woff2 suffices everywhere modern.
    $css = preg_replace('/,\s*\n?\s*url\([\'"]?(?:https:)?\/\/[^)\'"]+[\'"]?\)\s*format\([\'"]?(?:woff|truetype)[\'"]?\)/', '', $css) ?? $css;
    $cssAll .= "/* {$cssUrl} */\n" . $css . "\n";
}

file_put_contents($cssOut, $cssAll);
echo "fonts.css written — {$count} files in public/fonts\n";
