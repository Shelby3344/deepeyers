<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockSensitivePaths
{
    /**
     * Paths that should NEVER be accessible
     */
    private array $blockedPaths = [
        '.env',
        '.env.example',
        '.env.backup',
        '.env.production',
        '.git',
        '.gitignore',
        '.htaccess',
        '.htpasswd',
        'artisan',
        'composer.json',
        'composer.lock',
        'package.json',
        'package-lock.json',
        'phpunit.xml',
        'webpack.mix.js',
        'vite.config.js',
        'server.php',
        'config/',
        'storage/logs',
        'storage/framework',
        'database/',
        'bootstrap/',
        'vendor/',
        'app/',
        'routes/',
        'resources/',
        'tests/',
    ];

    /**
     * Blocked file extensions
     */
    private array $blockedExtensions = [
        'env',
        'log',
        'sql',
        'sqlite',
        'bak',
        'backup',
        'old',
        'orig',
        'swp',
        'tmp',
        'ini',
        'conf',
        'yml',
        'yaml',
        'lock',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        $pathLower = strtolower($path);

        // Block direct access to sensitive paths
        foreach ($this->blockedPaths as $blocked) {
            if (str_starts_with($pathLower, strtolower($blocked))) {
                abort(404);
            }
        }

        // Block sensitive file extensions
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if (in_array(strtolower($extension), $this->blockedExtensions)) {
            abort(404);
        }

        // Block path traversal attempts
        if (str_contains($path, '..') || str_contains($path, '%2e%2e')) {
            abort(403, 'Forbidden');
        }

        // Block null byte injection
        if (str_contains($path, "\0") || str_contains($path, '%00')) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
