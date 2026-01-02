```markdown
# Vergissmeinnicht - Typecho Theme

[![Typecho](https://img.shields.io/badge/Typecho-1.2+-467fcf.svg)](http://typecho.org)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**Vergissmeinnicht** 是一款基于 Typecho 的极简风格博客主题，采用现代化的 **毛玻璃拟态 (Glassmorphism)** 设计语言，融合了高性能的 **PJAX 无刷新加载**、**Canvas 动态天气系统** 以及优雅的 **文章阅读体验**。

它不仅是一个博客皮肤，更是一次关于视觉与交互的探索。

## ✨ 核心特性

### 🎨 视觉设计
- **全站毛玻璃拟态**：卡片、侧边栏、导航栏均采用高质量的 Backdrop Filter 模糊效果，质感细腻。
- **动态昼夜模式**：
  - **日间模式**：温暖的橙色光斑呼吸效果，搭配清爽的玻璃质感。
  - **夜间模式**：深邃的蓝紫色光斑，搭配深色半透明界面，沉浸感十足。
- **自适应布局**：完美适配移动端、平板和桌面端，侧边栏智能折叠。

### ⚡ 交互与性能
- **全站 PJAX**：基于 jQuery PJAX 实现页面无刷新跳转，音乐不中断，体验如丝般顺滑。
- **Canvas 天气系统**：
  - **高性能**：纯 Canvas 绘制雨雪粒子，GPU 加速，CPU 占用极低。
  - **智能遮罩**：白天模式下雨时，自动触发“乌云压顶”效果（蓝灰色遮罩），增强沉浸感并保证雨滴清晰可见。
  - **状态保持**：切换页面时天气状态不重置。
- **原生图片灯箱**：
  - 无需第三方库，原生 JS 实现。
  - 支持 **滚轮缩放**、**鼠标拖拽**（无延迟跟手）、**键盘切换**。
  - 自动识别文章内图片生成画廊。

### 📝 阅读体验
- **极致排版 (Typography)**：
  - 专门优化的中文排版，收紧段落间距，消除富文本编辑器的冗余空行。
  - 标题采用“荧光笔划重点”风格的半透明下划线。
  - 代码块、引用块、列表均经过精细化设计。
- **信息卡片**：文章头部自动生成“预计阅读时间”、“字数统计”及“最后更新时间”卡片。
- **代码高亮**：集成 Prism.js，支持多种语言高亮，并配备 Mac 风格窗口栏和“一键复制”功能。

## 🛠️ 安装说明

1.  下载本项目源码。
2.  将文件夹解压并重命名为 `Vergissmeinnicht`（或者你喜欢的名字）。
3.  上传至 Typecho 的 `/usr/themes/` 目录下。
4.  进入 Typecho 后台 -> **控制台** -> **外观**，启用该主题。
5.  进入 **设置外观**，配置你的个人信息（Logo、简介、社交链接等）。

## ⚙️ 目录结构

```text
Vergissmeinnicht/
├── assets/
│   └── main/
│       ├── main.css       # 核心样式表 (包含所有模块)
│       ├── main.js        # 核心逻辑 (PJAX, 灯箱, 菜单)
│       ├── weather.js     # 天气系统逻辑
│       └── prism.js       # 代码高亮库
├── template-parts/        # 模板片段
│   ├── header.php         # 头部 (包含 HTML 骨架)
│   ├── footer.php         # 底部 (包含 PJAX 绑定)
│   ├── sidebar.php        # 右侧边栏
│   ├── post-default.php   # 文章内容模板
│   └── ...
├── index.php              # 首页
├── post.php               # 文章页
├── page.php               # 独立页面
├── functions.php          # 主题函数
└── style.css              # 主题信息声明

```

## 🔧 开发者指南

如果你想修改样式，推荐使用内置的构建工具来保持代码整洁。

1. **安装依赖**（需 Node.js 环境）：
```bash
npm install

```


*(注：其实本主题核心是一个 `build-css.js` 脚本，无复杂的 npm 依赖，直接用 node 运行即可)*
2. **修改样式**：
所有样式源文件位于 `assets/main/modules/` 目录下（如 `header.css`, `sidebar.css` 等）。
3. **构建 CSS**：
修改完后，运行以下命令合并 CSS，以获得最佳性能：
```bash
node build-css.js --prod

```



## 🐞 常见问题

**Q: 为什么切换页面时天气图标不亮了？**
A: 这是一个已知的 PJAX 回调问题，但已在 v4.0 版本中修复。JS 会在每次 PJAX `complete` 时自动重载天气逻辑。

**Q: 为什么白天模式下雨时背景变灰了？**
A: 这是特意设计的“乌云”效果。为了让白色的雨滴在亮色背景下清晰可见，我们在白天雨雪模式下加了一层 20% 透明度的蓝灰色遮罩。

**Q: 图片拖拽感觉有延迟？**
A: 请确保使用了最新版的 `main.js`。我们引入了 `.dragging` 类，在拖拽瞬间禁用了 CSS `transition`，实现了 0 延迟跟手。

## 📜 开源协议

本项目遵循 [MIT License](https://www.google.com/search?q=LICENSE) 协议开源。
你可以自由地使用、修改和分发本主题，但请保留底部的版权声明。

---

**Made with ❤️ by XiNian_dada**

```

```