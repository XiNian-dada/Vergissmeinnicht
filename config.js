// config.js - CSS构建配置
const config = {
    // 当前模式：'development' 或 'production'
    mode: 'production',
    
    // 文件路径配置
    paths: {
        baseDir: './assets/main/',
        modulesDir: './assets/main/modules/',
        mainCSS: './assets/main/main.css',
        prodCSS: './assets/main/main.min.css'
    },
    
    // CSS模块加载顺序（重要：必须按依赖顺序）
    modules: [
        'variables.css',      // 必须第一个
        'base.css',
        'glass-morphism.css',
        'layout.css',
        'sidebar-left.css',
        'sidebar-right.css',
        'content.css',
        'navigation.css',
        'forms.css',
        'code-blocks.css',
        'components.css',
        'image-zoom.css',
        'mobile.css'          // 最后加载
    ],
    
    // 构建选项
    build: {
        minify: true,           // 是否压缩CSS
        removeComments: true,   // 是否移除注释
        addTimestamp: true,     // 是否添加构建时间戳
        backup: true           // 是否备份原文件
    },
    
    // 开发服务器配置（可选）
    dev: {
        autoReload: false,     // 是否自动重载（需要额外配置）
        sourceMap: false       // 是否生成source map
    }
};

module.exports = config;