const fs = require('fs');
const path = require('path');

// ==========================================
// 1. 配置区域 (根据你的文件结构设定)
// ==========================================
const CONFIG = {
    // 基础路径：assets/main
    basePath: path.join(__dirname, 'assets', 'main'),
    // 模块目录名
    modulesDirName: 'modules',
    // 输出文件名
    outputFile: 'main.css',
    
    // ★★★ CSS 合并顺序 (非常重要，不要乱) ★★★
    modules: [
        'variables.css',   // 1. 变量定义 (必须最先)
        'resets.css',      // 2. 重置样式
        'layout.css',      // 3. 基础布局
        'typography.css',  // 4. 排版字体
        'components.css',  // 5. 通用组件
        'header.css',      // 6. 头部
        'footer.css',      // 7. 底部
        'sidebar.css',     // 8. 侧边栏
        'posts.css',       // 9. 文章样式
        'pagination.css',  // 10. 分页
        'codeblock.css',   // 11. 代码块
        'lightbox.css',    // 12. 灯箱
        'weather.css'      // 13. 天气
    ]
};

// ==========================================
// 2. 构建逻辑类
// ==========================================
class CSSBuilder {
    constructor() {
        this.modulesPath = path.join(CONFIG.basePath, CONFIG.modulesDirName);
        this.outputPath = path.join(CONFIG.basePath, CONFIG.outputFile);
    }

    log(msg, type = 'info') {
        const icon = { info: 'ℹ️', success: '✅', error: '❌', warn: '⚠️' };
        console.log(`${icon[type] || ''} ${msg}`);
    }

    // 简单的 CSS 压缩器 (移除注释和多余空格)
    minify(css) {
        return css
            .replace(/\/\*[\s\S]*?\*\//g, '') // 移除注释
            .replace(/\s+/g, ' ')              // 压缩空白
            .replace(/\s*([{}:;,])\s*/g, '$1') // 移除符号周围空格
            .replace(/;}/g, '}')               // 移除末尾分号
            .trim();
    }

    // 检查文件完整性
    checkFiles() {
        let allExist = true;
        CONFIG.modules.forEach(file => {
            const filePath = path.join(this.modulesPath, file);
            if (!fs.existsSync(filePath)) {
                this.log(`文件缺失: ${file}`, 'error');
                allExist = false;
            }
        });
        return allExist;
    }

    // --- 生产模式：合并并压缩 ---
    buildProd() {
        this.log('正在构建 [生产模式] (合并压缩)...');
        
        if (!this.checkFiles()) return;

        let combinedCSS = `/* Typecho Theme Production Build - ${new Date().toLocaleString()} */\n`;

        CONFIG.modules.forEach(file => {
            const filePath = path.join(this.modulesPath, file);
            const content = fs.readFileSync(filePath, 'utf8');
            // 写入模块标识，方便排查，但在压缩后其实不明显
            combinedCSS += this.minify(content);
        });

        fs.writeFileSync(this.outputPath, combinedCSS);
        
        const size = (fs.statSync(this.outputPath).size / 1024).toFixed(2);
        this.log(`构建成功！文件已生成: ${CONFIG.outputFile} (${size} KB)`, 'success');
        this.log('现在你的网站只加载这一个CSS文件，速度起飞！', 'success');
    }

    // --- 开发模式：使用 @import ---
    buildDev() {
        this.log('正在切换到 [开发模式] (@import)...');

        let importContent = `/* Typecho Theme Development Mode - ${new Date().toLocaleString()} */\n`;
        importContent += `/* 注意：此模式仅用于开发调试，性能较差 */\n\n`;

        CONFIG.modules.forEach(file => {
            importContent += `@import url('./${CONFIG.modulesDirName}/${file}');\n`;
        });

        fs.writeFileSync(this.outputPath, importContent);
        this.log(`已切换至开发模式。main.css 现在引用各个子文件。`, 'success');
    }
}

// ==========================================
// 3. 执行入口
// ==========================================
const builder = new CSSBuilder();
const args = process.argv.slice(2);

// 简单参数解析
if (args.includes('--prod') || args.includes('-p')) {
    builder.buildProd();
} else if (args.includes('--dev') || args.includes('-d')) {
    builder.buildDev();
} else {
    // 默认行为（无参数时）
    console.log('------------------------------------------------');
    console.log('  CSS 构建工具使用指南');
    console.log('------------------------------------------------');
    console.log('  npm run build      -> 生成生产版本 (合并压缩)');
    console.log('  node build-css.js --prod  -> 同上');
    console.log('  node build-css.js --dev   -> 切换回开发模式 (@import)');
    console.log('------------------------------------------------');
    
    // 为了方便，无参数时默认执行生产构建
    console.log('未指定参数，默认执行生产构建...');
    builder.buildProd();
}