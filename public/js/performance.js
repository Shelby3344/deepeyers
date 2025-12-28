// Performance optimizations
(function() {
    'use strict';
    
    // Lazy load Font Awesome icons
    function loadFontAwesome() {
        if (document.querySelector('link[href*="font-awesome"]')) return;
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css';
        link.media = 'print';
        link.onload = function() { this.media = 'all'; };
        document.head.appendChild(link);
    }
    
    // Lazy load Google Fonts
    function loadGoogleFonts() {
        if (document.querySelector('link[href*="fonts.googleapis"]')) return;
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap';
        link.media = 'print';
        link.onload = function() { this.media = 'all'; };
        document.head.appendChild(link);
    }
    
    // Preconnect to external domains
    function addPreconnects() {
        const domains = [
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
            'https://cdnjs.cloudflare.com'
        ];
        domains.forEach(domain => {
            if (!document.querySelector(`link[href="${domain}"]`)) {
                const link = document.createElement('link');
                link.rel = 'preconnect';
                link.href = domain;
                link.crossOrigin = 'anonymous';
                document.head.appendChild(link);
            }
        });
    }
    
    // Defer non-critical images
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                });
            }, { rootMargin: '50px' });
            images.forEach(img => observer.observe(img));
        } else {
            images.forEach(img => {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            });
        }
    }
    
    // Remove loading screen
    function hideLoadingScreen() {
        const loader = document.getElementById('loadingScreen');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => loader.remove(), 300);
        }
    }
    
    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    function init() {
        addPreconnects();
        loadGoogleFonts();
        loadFontAwesome();
        lazyLoadImages();
        
        // Hide loader after fonts load or timeout
        Promise.race([
            document.fonts.ready,
            new Promise(r => setTimeout(r, 2000))
        ]).then(hideLoadingScreen);
    }
})();
