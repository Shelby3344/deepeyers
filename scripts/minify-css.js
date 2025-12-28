const CleanCSS = require('clean-css');
const fs = require('fs');
const path = require('path');

const cssDir = path.join(__dirname, '../public/css');
const files = ['deepeyes.css', 'mobile.css', 'tailwind.css'];

console.log('🎨 Minificando arquivos CSS...\n');

files.forEach(file => {
    const filePath = path.join(cssDir, file);
    
    if (fs.existsSync(filePath)) {
        const input = fs.readFileSync(filePath, 'utf8');
        const output = new CleanCSS({
            level: 2,
            compatibility: '*'
        }).minify(input);
        
        fs.writeFileSync(filePath, output.styles);
        
        const originalSize = (input.length / 1024).toFixed(2);
        const minifiedSize = (output.styles.length / 1024).toFixed(2);
        const savings = ((1 - output.styles.length / input.length) * 100).toFixed(1);
        
        console.log(`✅ ${file}: ${originalSize}KB → ${minifiedSize}KB (${savings}% redução)`);
    } else {
        console.log(`⚠️  ${file}: arquivo não encontrado`);
    }
});

console.log('\n✨ CSS minificado com sucesso!');
