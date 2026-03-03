# Vergissmeinnicht — Typecho Theme

![Typecho](https://img.shields.io/badge/Typecho-1.2+-467fcf.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

**Vergissmeinnicht** 是一款基于 **Typecho** 的极简博客主题，设计语言以 **Glassmorphism（毛玻璃拟态）** 为核心，强调克制、沉浸与阅读本身。

主题在视觉表现与工程实现之间保持平衡：
不过度堆砌动画，但在关键细节上追求完成度。

---

## 📸 主题预览

![Preview 1](https://github.com/XiNian-dada/Vergissmeinnicht/blob/main/1.png)
![Preview 2](https://github.com/XiNian-dada/Vergissmeinnicht/blob/main/2.png)
![Preview 3](https://github.com/XiNian-dada/Vergissmeinnicht/blob/main/3.png)
![Preview 4](https://github.com/XiNian-dada/Vergissmeinnicht/blob/main/4.png)

---

## ✨ 核心特性

### 🎨 视觉设计

* **全站毛玻璃拟态**

  * 卡片 / 导航栏 / 侧边栏统一使用 Backdrop Filter
  * 模糊层级经过实际阅读测试，保证文字对比度
* **昼夜模式**

  * **日间**：浅色玻璃 + 暖色光斑呼吸效果
  * **夜间**：深色半透明界面 + 蓝紫色光斑，降低视觉疲劳
* **自适应布局**

  * 桌面 / 平板 / 移动端完整适配
  * 侧边栏在小屏设备下自动折叠

---

### ⚡ 交互与性能

* **全站 PJAX 无刷新加载**

  * 页面切换无白屏
  * 音乐、Canvas 状态不中断
* **Canvas 天气系统**

  * 纯 Canvas 实现雨 / 雪粒子效果
  * GPU 加速，CPU 占用极低
  * 天气状态在 PJAX 切换时保持
  * 白天雨雪模式自动启用「乌云遮罩」，保证可读性
* **原生图片灯箱**

  * 无第三方依赖
  * 支持：

    * 滚轮缩放
    * 鼠标拖拽（0 延迟）
    * 键盘切换
  * 自动识别文章内图片并生成画廊

---

### 📝 阅读体验

* **中文排版优化**

  * 收紧段落间距
  * 移除富文本编辑器常见的冗余空行
* **标题强调样式**

  * 半透明“荧光笔”下划线
* **文章信息卡片**

  * 阅读时间
  * 字数统计
  * 最后更新时间
* **代码高亮**

  * 基于 Prism.js
  * Mac 风格代码窗口
  * 一键复制代码

---

## 🛠️ 安装方式

1. 下载主题源码
2. 解压并重命名为 `Vergissmeinnicht`
3. 上传至：

   ```
   /usr/themes/
   ```
4. 后台 → **外观** → 启用主题
5. 在 **外观设置** 中填写站点信息（头像、简介、社交链接等）

---

## ⚙️ 目录结构

```text
Vergissmeinnicht/
├── assets/
│   └── main/
│       ├── main.css        # 合并后的核心样式
│       ├── main.js         # PJAX / 灯箱 / 菜单逻辑
│       ├── weather.js      # Canvas 天气系统
│       └── prism.js        # 代码高亮
├── template-parts/
│   ├── header.php
│   ├── footer.php
│   ├── sidebar.php
│   ├── post-default.php
│   └── ...
├── index.php
├── post.php
├── page.php
├── functions.php
└── style.css               # 主题声明
```

---

## 🔧 开发说明

如果你需要二次开发样式，建议使用内置构建脚本。

### 1️⃣ 安装依赖

```bash
npm install
```

> 实际上仅依赖一个 `build-css.js`，无复杂构建链

### 2️⃣ 修改样式

模块化 CSS 位于：

```
assets/main/modules/
```

### 3️⃣ 构建

```bash
node build-css.js --prod
```

构建后会生成压缩合并的 `main.css`，减少请求数。

---

## 🐞 常见问题

**Q：PJAX 切换后天气状态丢失？**
A：已在 v4.0 修复，天气逻辑会在每次 PJAX `complete` 时自动重载。

**Q：白天雨天背景为什么会变灰？**
A：这是刻意设计的遮罩层，用于保证浅色背景下雨滴可见性。

**Q：图片拖拽有延迟？**
A：请确认使用最新版 `main.js`，拖拽时会禁用 CSS transition。

---

## 📜 License

MIT License
允许自由使用、修改和分发，请保留版权声明。

---

**Made by XiNian_dada**
