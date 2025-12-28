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
     * Minifica o HTML
     */
    private function minify(string $html): string
    {
        // Remove comentários HTML (exceto condicionais do IE)
        $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);
        
        // Remove espaços em branco extras entre tags
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Remove espaços em branco no início e fim de linhas
        $html = preg_replace('/^\s+|\s+$/m', '', $html);
        
        // Remove múltiplas linhas em branco
        $html = preg_replace('/\n\s*\n/', "\n", $html);
        
        // Minifica CSS inline
        $html = preg_replace_callback('/<style[^>]*>(.*?)<\/style>/is', function($matches) {
            $css = $matches[1];
            // Remove comentários CSS
            $css = preg_replace('/\/\*.*?\*\//s', '', $css);
            // Remove espaços extras
            $css = preg_replace('/\s+/', ' ', $css);
            // Remove espaços ao redor de caracteres especiais
            $css = preg_replace('/\s*([{};:,>+~])\s*/', '$1', $css);
            // Remove último ponto e vírgula antes de }
            $css = str_replace(';}', '}', $css);
            return '<style>' . trim($css) . '</style>';
        }, $html);
        
        // Minifica JS inline (básico - não quebra strings)
        $html = preg_replace_callback('/<script[^>]*>(.*?)<\/script>/is', function($matches) {
            $js = $matches[1];
            if (empty(trim($js))) return '<script></script>';
            
            // Remove comentários de linha única (cuidado com URLs)
            $js = preg_replace('/(?<!:)\/\/(?!["\']).*$/m', '', $js);
            // Remove comentários de bloco
            $js = preg_replace('/\/\*.*?\*\//s', '', $js);
            // Remove espaços extras (preservando strings)
            $js = preg_replace('/\s+/', ' ', $js);
            
            return '<script>' . trim($js) . '</script>';
        }, $html);
        
        return trim($html);
    }
}
