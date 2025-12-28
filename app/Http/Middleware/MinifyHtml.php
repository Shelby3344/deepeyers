<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyHtml
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Só minifica em produção e se for HTML
        if (!app()->environment('production')) {
            return $response;
        }

        $contentType = $response->headers->get('Content-Type');
        
        if ($contentType && str_contains($contentType, 'text/html')) {
            $content = $response->getContent();
            $minified = $this->minify($content);
            $response->setContent($minified);
        }

        return $response;
    }

    /**
     * Minifica o HTML de forma segura
     */
    private function minify(string $html): string
    {
        // Remove comentários HTML (exceto condicionais do IE)
        $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);
        
        // Remove múltiplas linhas em branco, mas mantém estrutura
        $html = preg_replace('/\n\s*\n/', "\n", $html);
        
        // Remove espaços no início das linhas
        $html = preg_replace('/^\s+/m', '', $html);
        
        // Remove quebras de linha extras (junta tudo em menos linhas)
        $html = preg_replace('/\n+/', "\n", $html);
        
        return trim($html);
    }
}
