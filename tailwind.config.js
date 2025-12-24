/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        'de-bg': '#0a0a0f',
        'de-bg-secondary': '#12121a',
        'de-bg-tertiary': '#1a1a24',
        'de-neon': '#00FF88',
        'de-cyan': '#00D4FF',
        'de-purple': '#8b5cf6',
        'de-red': '#EF4444',
        'de-orange': '#F97316',
        'de': {
          'dark': '#0B0F14',
          'darker': '#070A0E',
          'card': '#0F172A',
          'border': 'rgba(255,255,255,0.08)',
          'green': '#00FF88',
          'cyan': '#00D4FF',
          'orange': '#F97316',
          'red': '#EF4444',
        }
      },
      fontFamily: {
        'display': ['Inter', 'sans-serif'],
        'mono': ['JetBrains Mono', 'monospace'],
        'grotesk': ['Space Grotesk', 'sans-serif'],
      }
    }
  },
  plugins: [],
}
