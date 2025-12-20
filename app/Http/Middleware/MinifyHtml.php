<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyHtml
{
    /**
     * Handle an incoming request.
     * Minifica o HTML removendo espaços, quebras de linha e comentários
     * para dificultar a leitura do código fonte.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Só minifica se for resposta HTML
        if ($this->isHtmlResponse($response)) {
            $content = $response->getContent();
            $minified = $this->minify($content);
            $response->setContent($minified);
        }

        return $response;
    }

    /**
     * Verifica se a resposta é HTML
     */
    private function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type');
        return $contentType && str_contains($contentType, 'text/html');
    }

    /**
     * Minifica o HTML
     */
    private function minify(string $html): string
    {
        // Preserva conteúdo de <script>, <style>, <pre>, <textarea>
        $preserved = [];
        $patterns = [
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/<style\b[^>]*>(.*?)<\/style>/is',
            '/<pre\b[^>]*>(.*?)<\/pre>/is',
            '/<textarea\b[^>]*>(.*?)<\/textarea>/is',
        ];

        // Substitui temporariamente blocos que devem ser preservados
        foreach ($patterns as $index => $pattern) {
            $html = preg_replace_callback($pattern, function ($matches) use (&$preserved, $index) {
                $key = "<!--PRESERVED_{$index}_" . count($preserved) . "-->";
                $preserved[$key] = $matches[0];
                return $key;
            }, $html);
        }

        // Remove comentários HTML (exceto condicionais do IE)
        $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);

        // Remove espaços em branco entre tags
        $html = preg_replace('/>\s+</', '><', $html);

        // Remove quebras de linha e tabs
        $html = preg_replace('/[\r\n\t]+/', '', $html);

        // Remove espaços múltiplos
        $html = preg_replace('/\s{2,}/', ' ', $html);

        // Remove espaços no início e fim
        $html = trim($html);

        // Restaura blocos preservados
        foreach ($preserved as $key => $value) {
            // Minifica também o conteúdo de scripts inline (não external)
            if (preg_match('/<script\b[^>]*>(.+)<\/script>/is', $value, $m) && !preg_match('/src\s*=/', $value)) {
                $minifiedScript = $this->minifyJs($m[1]);
                $value = preg_replace('/<script(\b[^>]*)>(.+)<\/script>/is', '<script$1>' . $minifiedScript . '</script>', $value);
            }
            
            // Minifica CSS inline
            if (preg_match('/<style\b[^>]*>(.+)<\/style>/is', $value, $m)) {
                $minifiedCss = $this->minifyCss($m[1]);
                $value = preg_replace('/<style(\b[^>]*)>(.+)<\/style>/is', '<style$1>' . $minifiedCss . '</style>', $value);
            }
            
            $html = str_replace($key, $value, $html);
        }

        return $html;
    }

    /**
     * Minifica JavaScript básico
     */
    private function minifyJs(string $js): string
    {
        // Remove comentários de linha
        $js = preg_replace('/\/\/(?![\'"]).*/m', '', $js);
        
        // Remove comentários de bloco
        $js = preg_replace('/\/\*[\s\S]*?\*\//', '', $js);
        
        // Remove quebras de linha e espaços excessivos
        $js = preg_replace('/\s*\n\s*/', '', $js);
        $js = preg_replace('/\s{2,}/', ' ', $js);
        
        // Remove espaços desnecessários ao redor de operadores
        $js = preg_replace('/\s*([{};,:])\s*/', '$1', $js);
        
        return trim($js);
    }

    /**
     * Minifica CSS básico
     */
    private function minifyCss(string $css): string
    {
        // Remove comentários
        $css = preg_replace('/\/\*[\s\S]*?\*\//', '', $css);
        
        // Remove quebras de linha e espaços excessivos
        $css = preg_replace('/\s*\n\s*/', '', $css);
        $css = preg_replace('/\s{2,}/', ' ', $css);
        
        // Remove espaços desnecessários
        $css = preg_replace('/\s*([{};:,>~+])\s*/', '$1', $css);
        
        return trim($css);
    }
}
