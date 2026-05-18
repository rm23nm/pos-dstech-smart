const fs = require('fs');
const vm = require('vm');

const content = fs.readFileSync('resources/views/Transaksi/Penjualan/PoS/FnBPoS.blade.php', 'utf8');

const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gi;
let match;
let scriptIndex = 0;
let hasError = false;

while ((match = scriptRegex.exec(content)) !== null) {
    scriptIndex++;
    let jsCode = match[1];
    
    // Replace PHP inline tags cleanly first
    jsCode = jsCode.replace(/<\?php[\s\S]*?\?>/gi, '"php_placeholder"');
    jsCode = jsCode.replace(/['"]\{\{[\s\S]*?\}\}['"]/gi, '"blade_placeholder"');
    jsCode = jsCode.replace(/\{\{[\s\S]*?\}\}/gi, 'null');
    jsCode = jsCode.replace(/@\{\{[\s\S]*?\}\}/gi, 'null');
    jsCode = jsCode.replace(/@\w+\([\s\S]*?\)/gi, '/* directive */');
    jsCode = jsCode.replace(/@\w+/gi, '/* directive */');

    try {
        new vm.Script(jsCode);
        console.log(`Script block ${scriptIndex}: OK`);
    } catch (e) {
        hasError = true;
        console.error(`Script block ${scriptIndex}: ERROR`);
        console.error(e.message);
        
        const lines = jsCode.split('\n');
        const stackLines = e.stack.split('\n');
        const lineMatch = stackLines[0].match(/:(\d+)$/) || stackLines[1].match(/:(\d+):\d+$/) || stackLines[1].match(/:(\d+)$/);
        if (lineMatch) {
            const errLineNum = parseInt(lineMatch[1]);
            console.error(`Error at line ${errLineNum}:`);
            for (let i = Math.max(0, errLineNum - 5); i < Math.min(lines.length, errLineNum + 5); i++) {
                const marker = (i + 1 === errLineNum) ? '>>> ' : '    ';
                console.error(`${marker}${i + 1}: ${lines[i]}`);
            }
        } else {
            console.error(e.stack);
        }
        console.error('\n' + '='.repeat(40) + '\n');
    }
}

if (!hasError) {
    console.log("ALL SCRIPT BLOCKS COMPILATION PASSED!");
}
