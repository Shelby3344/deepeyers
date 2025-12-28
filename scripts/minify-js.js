const { minify } = require('terser');
const fs = require('fs');
const path = require('path');

const jsDir = path.join(__dirname, '../public/js');

async function minifyJS() {
    console.log('📦 Minificando arquivos JS...\n');
    
    if (!fs.existsSync(jsDir)) {
        console.log('ℹ️  Pasta public/js não encontrada. Pulando minificação JS.');
        return;
    }
    
    const files = fs.readdirSync(jsDir).filter(f => f.endsWith('.js') && !f.endsWith('.min.js'));
    
    for (const file of files) {
        const filePath = path.join(jsDir, file);
        const input = fs.readFileSync(filePath, 'utf8');
        
        try {
            const result = await minify(input, {
                compress: {
                    drop_console: false,
                    drop_debugger: true
                },
                mangle: true,
                format: {
                    comments: false
                }
            });
            
            fs.writeFileSync(filePath, result.code);
            
            const originalSize = (input.length / 1024).toFixed(2);
            const minifiedSize = (result.code.length / 1024).toFixed(2);
            const savings = ((1 - result.code.length / input.length) * 100).toFixed(1);
            
            console.log(`✅ ${file}: ${originalSize}KB → ${minifiedSize}KB (${savings}% redução)`);
        } catch (err) {
            console.log(`❌ ${file}: erro ao minificar - ${err.message}`);
        }
    }
    
    console.log('\n✨ JS minificado com sucesso!');
}

minifyJS();
