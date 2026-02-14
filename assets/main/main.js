/**
 * Lycorisradiata Lite Main JS (Ultimate Refactored)
 */

// =========================================
// 0. 全局工具函数
// =========================================

// 天气插件重试逻辑
window.retryWeather = function() {
    const weatherId = 'ww_a063b4105b215'; 
    
    const $box = document.getElementById(weatherId);
    if(!$box) return;

    $box.innerHTML = '<div style="text-align:center;padding-top:40%;color:#999;font-size:12px;">正在连接...</div>';
    
    const oldScript = document.querySelector('script[src*="' + weatherId + '"]');
    if(oldScript) oldScript.remove();
    
    const script = document.createElement('script');
    script.src = 'https://app3.weatherwidget.org/js/?id=' + weatherId;
    script.async = true;
    $box.parentNode.appendChild(script);
};

// 侧边栏滚动隐藏逻辑 (防抖动优化版)
window.initScrollDock = function() {
    // 移除旧的监听器防止重复叠加
    if (window.handleDockScroll) {
        window.removeEventListener('scroll', window.handleDockScroll);
    }

    var dock = document.querySelector('.sidebar-dock');
    var scrollTimer = null;

    // 定义滚动处理函数
    window.handleDockScroll = function() {
        // 如果抽屉菜单是打开的，禁止胶囊出来！(互斥逻辑)
        if (document.querySelector('#mobile-drawer.active')) return;

        // 如果没有找到元素，或者当前是大屏幕，直接不处理
        if (!dock || window.innerWidth > 992) {
            if(dock) dock.classList.remove('dock-hidden'); // 确保大屏下总是显示的
            return;
        }

        // 1. 只要发生滚动，立刻添加隐藏类
        dock.classList.add('dock-hidden');

        // 2. 清除之前的计时器
        if (scrollTimer) clearTimeout(scrollTimer);

        // 3. 设置新计时器：停止滚动 200ms 后显示
        scrollTimer = setTimeout(function() {
            // 再次检查抽屉状态，确保安全
            if (!document.querySelector('#mobile-drawer.active')) {
                dock.classList.remove('dock-hidden');
            }
        }, 200);
    };

    // 绑定监听
    window.addEventListener('scroll', window.handleDockScroll, { passive: true });
};

// =========================================
// 核心修正：天气按钮逻辑
// =========================================
function initWeatherLogic() {
    if (window.WeatherSystem) {
        window.WeatherSystem.init();
    }

    const btn = document.getElementById('weather-toggle');
    if (btn && window.WeatherSystem) {
        // 1. 初始化时，按钮显示【当前】正在运行的天气
        const currentIcon = window.WeatherSystem.getCurrentIconClass();
        btn.innerHTML = `<i class="ti ${currentIcon}"></i>`;

        // 克隆节点去除旧监听器
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // 2. 点击切换，并获取【切换后】的图标
            const newIconClass = window.WeatherSystem.toggleNext();
            
            // 3. 立即更新按钮图标
            newBtn.innerHTML = `<i class="ti ${newIconClass}"></i>`;
        });
    }
}

// =========================================
// 主重载函数 (供 PJAX 和 onload 调用)
// =========================================
window.reloadThemeComponents = function() {
    console.log('Lycorisradiata Lite: Reloading components...');

    // 1. 初始化 Mac 风格代码块
    initMacCodeBlock();

    // 2. 初始化图片灯箱
    initLightbox();

    // 3. 初始化移动端菜单 (修正版)
    initMobileMenu();

    // 4. 初始化侧边栏滚动隐藏 (新增)
    window.initScrollDock();
    
    // 5. [新增] 初始化目录功能 (加在这里！)
    initTOC();

    // 5. 检查天气插件状态
    checkWeatherWidget();

    // ★★★ 核心修复：这里之前漏掉了！必须调用它才能让按钮生效 ★★★
    initWeatherLogic();
};

// =========================================
// Mac 风格代码块初始化 (终极逆向修复版)
// =========================================
// =========================================
// Mac 风格代码块初始化 (UEditor 终极适配版)
// =========================================
function initMacCodeBlock() {
    const preElements = document.querySelectorAll('pre');
    
    // 辅助函数：安全的 HTML 实体解码
    const decodeHTML = (str) => {
        const txt = document.createElement('textarea');
        txt.innerHTML = str;
        return txt.value;
    };

    preElements.forEach((pre) => {
        if (pre.parentElement.classList.contains('mac-window')) return;

        let code = pre.querySelector('code');
        const sourceContainer = code || pre;
        
        // --- 核心修复开始 ---
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = sourceContainer.innerHTML;

        // 1. 逆向还原 H 标签 (修复 # 被吃掉的问题)
        const headers = tempDiv.querySelectorAll('h1, h2, h3, h4, h5, h6');
        headers.forEach(header => {
            const level = parseInt(header.tagName.substring(1));
            let hashPrefix = '#'.repeat(level);
            // 智能补空格
            if (!header.textContent.replace(/\u00a0/g, ' ').startsWith(' ')) {
                hashPrefix += ' ';
            }
            const textNode = document.createTextNode(hashPrefix + header.textContent + '\n');
            header.replaceWith(textNode);
        });

        // 2. 处理常规换行 (把 br 变成换行符)
        tempDiv.querySelectorAll('br').forEach(br => {
            br.replaceWith(document.createTextNode('\n'));
        });
        
        // 3. 处理段落包裹 (把 p/div 变成换行) - 核心修复
        const blockTags = tempDiv.querySelectorAll('p, div, li');
        blockTags.forEach(block => {
            // 在块级元素后面添加换行
            const nextNode = block.nextSibling;
            if (!nextNode || nextNode.nodeType !== Node.TEXT_NODE || !nextNode.textContent.startsWith('\n')) {
                block.after(document.createTextNode('\n'));
            }
        });

        // 4. 获取初步清洗的 HTML 文本
        let htmlContent = tempDiv.innerHTML;
        
        // ★★★ 关键修复：处理被转义的换行符 ★★★
        // UEditor 有时会把换行符转义成 "\n" 字符串显示在 HTML 里
        // 如果你希望代码里的 \n 显示为字符，保留它；但如果它是排版用的，这里不乱动
        // 我们重点依靠上面的 br/p 替换来保证物理换行

        // 5. 提取纯文本 (利用 textContent 自动解码基础实体)
        // 注意：这里我们用 tempDiv 的 textContent，因为它已经处理了 H 标签和 br
        let cleanText = tempDiv.textContent || '';

        // ★★★ 关键修复：二次解码 &nbsp; 和其他残留实体 ★★★
        // 专门处理那些被 textContent 漏掉或者被二次转义的字符
        // 比如 &#39; (单引号) 在某些情况下 textContent 解码不彻底
        cleanText = decodeHTML(cleanText);

        // 6. 清理 &nbsp; (变成普通空格)
        cleanText = cleanText.replace(/\u00a0/g, ' '); 

        // 7. 清理多余空行 (保留最多两个连续换行)
        cleanText = cleanText.replace(/\n{3,}/g, '\n\n');
        cleanText = cleanText.trim();

        // --- 核心修复结束 ---

        if (!code) {
            code = document.createElement('code');
            pre.innerHTML = ''; 
            pre.appendChild(code);
        } else {
            code.innerHTML = '';
        }
        
        code.textContent = cleanText;
        
        // --- 语言识别 & Mac 窗口构建 (保持不变) ---
        let lang = 'TEXT';
        let prismClass = 'language-text';
        const extractLang = (cls) => {
            if (cls.startsWith('language-') || cls.startsWith('lang-')) {
                return { lang: cls.replace(/^(language-|lang-)/, '').toUpperCase(), cls: cls };
            }
            return null;
        };
        [code, pre].forEach(el => {
            el.classList.forEach(cls => {
                const res = extractLang(cls);
                if (res) { lang = res.lang; prismClass = res.cls; }
            });
        });
        if (lang === 'TEXT' && pre.className) {
            const match = pre.className.match(/brush\s*:\s*([a-zA-Z0-9]+)/);
            if(match) { lang = match[1].toUpperCase(); prismClass = 'language-' + match[1]; }
        }
        
        code.classList.remove('language-text');
        if (!code.classList.contains(prismClass)) code.classList.add(prismClass);

        const wrapper = document.createElement('div');
        wrapper.className = 'mac-window';
        wrapper.innerHTML = `
            <div class="mac-header">
                <div class="mac-dots">
                    <div class="mac-dot red"></div>
                    <div class="mac-dot yellow"></div>
                    <div class="mac-dot green"></div>
                </div>
                <div class="mac-lang">${lang}</div>
                <button class="mac-copy-btn">复制</button>
            </div>
        `;
        
        pre.parentNode.insertBefore(wrapper, pre);
        wrapper.appendChild(pre);

        const copyBtn = wrapper.querySelector('.mac-copy-btn');
        copyBtn.addEventListener('click', () => {
            navigator.clipboard.writeText(code.textContent).then(() => {
                const originalText = copyBtn.textContent;
                copyBtn.textContent = '已复制!';
                copyBtn.classList.add('copied');
                setTimeout(() => {
                    copyBtn.textContent = originalText;
                    copyBtn.classList.remove('copied');
                }, 2000);
            });
        });
    });

    if (window.Prism) Prism.highlightAll();
}
// =========================================
// 图片灯箱逻辑 (包含缩放、拖拽、切换)
// =========================================
function initLightbox() {
    // 1. 收集文章内所有图片
    const postImages = Array.from(document.querySelectorAll('.article-body img'));
    if (postImages.length === 0) return;

    // 2. 创建灯箱 DOM 结构
    if (!document.querySelector('.lightbox-overlay')) {
        const overlayHTML = `
            <div class="lightbox-overlay">
                <div class="lightbox-toolbar">
                    <span class="lightbox-counter"></span>
                    <button class="lightbox-close">&times;</button>
                </div>
                <button class="lightbox-nav lightbox-prev"><i class="ti ti-chevron-left"></i></button>
                <div class="lightbox-stage">
                    <img src="" class="lightbox-image" draggable="false">
                </div>
                <button class="lightbox-nav lightbox-next"><i class="ti ti-chevron-right"></i></button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', overlayHTML);
    }

    // 3. 获取 DOM 元素
    const overlay = document.querySelector('.lightbox-overlay');
    const stage = overlay.querySelector('.lightbox-stage');
    const imgEl = overlay.querySelector('.lightbox-image');
    const prevBtn = overlay.querySelector('.lightbox-prev');
    const nextBtn = overlay.querySelector('.lightbox-next');
    const closeBtn = overlay.querySelector('.lightbox-close');
    const counterEl = overlay.querySelector('.lightbox-counter');

    // 4. 状态变量
    let currentIndex = 0;
    let scale = 1;
    let pointX = 0;
    let pointY = 0;
    let isDragging = false;
    let startX = 0;
    let startY = 0;

    // ===========================
    // 核心功能函数
    // ===========================

    // 打开灯箱
    const openLightbox = (index) => {
        currentIndex = index;
        updateImage();
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // 禁止背景滚动
        // 绑定键盘事件
        document.addEventListener('keydown', handleKeydown);
    };

    // 关闭灯箱
    const closeLightbox = () => {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        document.removeEventListener('keydown', handleKeydown);
        // 重置状态
        setTimeout(() => {
            resetTransform();
            imgEl.src = ''; 
        }, 300);
    };

    // 更新图片和状态
    const updateImage = () => {
        // 边界循环
        if (currentIndex < 0) currentIndex = postImages.length - 1;
        if (currentIndex >= postImages.length) currentIndex = 0;

        const src = postImages[currentIndex].getAttribute('src');
        imgEl.src = src;
        
        // 更新计数器
        counterEl.textContent = `图 ${currentIndex + 1} / ${postImages.length}`;
        
        // 每次切换图片重置缩放和位置
        resetTransform();
    };

    // 重置变换
    const resetTransform = () => {
        scale = 1;
        pointX = 0;
        pointY = 0;
        updateTransform();
    };

    // 应用变换
    const updateTransform = () => {
        imgEl.style.transform = `translate(${pointX}px, ${pointY}px) scale(${scale})`;
    };

    // 键盘控制
    const handleKeydown = (e) => {
        if (!overlay.classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') prevBtn.click();
        if (e.key === 'ArrowRight') nextBtn.click();
    };

    // ===========================
    // 事件绑定
    // ===========================

    // A. 绑定文章内图片的点击事件
    postImages.forEach((img, index) => {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', (e) => {
            e.preventDefault(); // 防止可能的链接跳转
            openLightbox(index);
        });
    });

    // B. 按钮事件
    closeBtn.onclick = closeLightbox;
    prevBtn.onclick = (e) => { e.stopPropagation(); currentIndex--; updateImage(); };
    nextBtn.onclick = (e) => { e.stopPropagation(); currentIndex++; updateImage(); };
    
    // 点击遮罩空白处关闭 (但在拖拽时不关闭)
    overlay.onclick = (e) => {
        if (e.target === overlay || e.target === stage) {
            closeLightbox();
        }
    };

    // ===========================
    // 缩放与拖拽逻辑 (核心)
    // ===========================

    // 1. 滚轮缩放
    stage.addEventListener('wheel', (e) => {
        e.preventDefault();
        
        const delta = e.deltaY > 0 ? -0.1 : 0.1;
        const newScale = scale + delta;

        // 限制缩放倍数 0.5x ~ 5x
        if (newScale >= 0.5 && newScale <= 5) {
            scale = newScale;
            updateTransform();
        }
    });

    // 2. 鼠标/触摸拖拽
    const startDrag = (e) => {
        e.preventDefault();
        isDragging = true;
        
        // ★★★ 核心修改：开始拖拽时，给图片加上 dragging 类，禁用 CSS 过渡动画 ★★★
        imgEl.classList.add('dragging');
        
        startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
        startY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
        
        // 修改鼠标样式
        stage.style.cursor = 'grabbing';
    };

    const onDrag = (e) => {
        if (!isDragging) return;
        e.preventDefault();

        // 使用 requestAnimationFrame 优化性能，防止高刷新率屏幕下的抖动
        requestAnimationFrame(() => {
            const x = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
            const y = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;

            const diffX = x - startX;
            const diffY = y - startY;

            pointX += diffX;
            pointY += diffY;

            startX = x;
            startY = y;

            updateTransform();
        });
    };

    const stopDrag = () => {
        isDragging = false;
        
        // ★★★ 核心修改：停止拖拽后，移除 dragging 类，恢复 CSS 过渡（为了缩放平滑） ★★★
        imgEl.classList.remove('dragging');
        
        stage.style.cursor = 'grab';
    };

    // 绑定鼠标事件
    imgEl.addEventListener('mousedown', startDrag);
    window.addEventListener('mousemove', onDrag);
    window.addEventListener('mouseup', stopDrag);

    // 绑定触摸事件 (简单适配)
    imgEl.addEventListener('touchstart', startDrag);
    window.addEventListener('touchmove', onDrag);
    window.addEventListener('touchend', stopDrag);
}
function initTOC() {
    // ===============================================
    // ★ 步骤0：暴力清理 (修复换页残留问题)
    // ===============================================
    const appContainer = document.querySelector('.app-container');
    if (!appContainer) return;

    // 1. 移除可能存在的旧目录
    const oldToc = document.getElementById('left-sidebar-toc');
    if (oldToc) {
        oldToc.remove();
    }

    // 2. 移除容器上的布局类名 (恢复默认布局)
    appContainer.classList.remove('has-toc');
    
    // 3. 移除旧的滚动监听器 (防止报错)
    if (window.tocScrollHandler) {
        window.removeEventListener('scroll', window.tocScrollHandler);
        window.tocScrollHandler = null;
    }

    // ===============================================
    // ★ 步骤1：检查当前页面是否有新目录
    // ===============================================
    const content = document.querySelector('.article-body'); 
    const sourceToc = document.getElementById('toc-grid-source');

    // 如果当前页面没有目录源 (比如首页、归档页)，直接结束，保持页面干干净净
    if (!sourceToc) return;

    // ===============================================
    // ★ 步骤2：搬运目录
    // ===============================================
    sourceToc.style.display = 'block';
    sourceToc.id = 'left-sidebar-toc';
    sourceToc.className = 'toc-grid-item';
    appContainer.insertBefore(sourceToc, appContainer.firstChild);

    // ===============================================
    // ★ 步骤3：生成内容 & 开启监听
    // ===============================================
    const tocContainer = document.getElementById('toc-content');
    
    if (content && tocContainer) {
        const headers = content.querySelectorAll('h1, h2, h3, h4');
        
        if (headers.length > 0) {
            // 只有真的有标题时，才开启三栏布局
            appContainer.classList.add('has-toc');
            
            tocContainer.innerHTML = '';
            const ul = document.createElement('ul');
            const headerMap = [];

            headers.forEach((header, index) => {
                if (!header.id) header.id = 'toc-title-' + index;
                
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = '#' + header.id;
                a.textContent = header.innerText.replace('#', ''); 
                a.setAttribute('data-target', header.id);
                
                const tagName = header.tagName.toLowerCase();
                a.className = `toc-link toc-level-${tagName.replace('h','')}`;

                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.querySelectorAll('.toc-link').forEach(link => link.classList.remove('active'));
                    a.classList.add('active');
                    const offset = 100; 
                    const elementPosition = header.getBoundingClientRect().top + window.pageYOffset;
                    window.scrollTo({ top: elementPosition - offset, behavior: "smooth" });
                });

                li.appendChild(a);
                ul.appendChild(li);
                headerMap.push({ header: header, link: a });
            });
            tocContainer.appendChild(ul);

            // ScrollSpy 逻辑
            let isTicking = false;
            const updateActiveToc = () => {
                const scrollPosition = window.scrollY + 120; 
                let currentHeaderId = null;

                for (let i = 0; i < headerMap.length; i++) {
                    const item = headerMap[i];
                    // 必须重新获取位置，防止页面布局变化
                    const offsetTop = item.header.getBoundingClientRect().top + window.pageYOffset;
                    if (offsetTop <= scrollPosition) {
                        currentHeaderId = item.header.id;
                    } else {
                        break; 
                    }
                }

                if (currentHeaderId) {
                    document.querySelectorAll('.toc-link').forEach(link => {
                        if (link.getAttribute('data-target') === currentHeaderId) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    });
                } else if (window.scrollY < 50) {
                     document.querySelectorAll('.toc-link').forEach(link => link.classList.remove('active'));
                     if(headerMap.length > 0) headerMap[0].link.classList.add('active');
                }
                isTicking = false;
            };

            // 把监听器挂载到 window 对象上，方便下次清理
            window.tocScrollHandler = () => {
                if (!isTicking) {
                    window.requestAnimationFrame(updateActiveToc);
                    isTicking = true;
                }
            };
            window.addEventListener('scroll', window.tocScrollHandler, { passive: true });
            updateActiveToc(); // 初始化执行

        } else {
            sourceToc.style.display = 'none';
        }
    }
}
function initMobileMenu() {
    const menuTrigger = document.querySelector('#menu-trigger');
    const mobileDrawer = document.querySelector('#mobile-drawer'); 
    const sidebarDock = document.querySelector('.sidebar-dock'); 

    if (menuTrigger && mobileDrawer) {
        const newTrigger = menuTrigger.cloneNode(true);
        menuTrigger.parentNode.replaceChild(newTrigger, menuTrigger);

        newTrigger.onclick = function(e) { 
            e.stopPropagation(); 
            mobileDrawer.classList.toggle('active');
            newTrigger.classList.toggle('active');
            
            // 菜单打开时，隐藏胶囊
            if (mobileDrawer.classList.contains('active')) {
                if(sidebarDock) sidebarDock.classList.add('dock-hidden');
            } else {
                if(sidebarDock) sidebarDock.classList.remove('dock-hidden');
            }

            const icon = newTrigger.querySelector('i');
            if (mobileDrawer.classList.contains('active')) {
                if(icon) icon.classList.replace('ti-menu-2', 'ti-x');
            } else {
                if(icon) icon.classList.replace('ti-x', 'ti-menu-2');
            }
        };
        
        if (!window.menuClickOutsideBound) {
            document.addEventListener('click', function(e) {
                if (mobileDrawer.classList.contains('active') && !mobileDrawer.contains(e.target) && !newTrigger.contains(e.target)) {
                    mobileDrawer.classList.remove('active');
                    newTrigger.classList.remove('active');
                    
                    if(sidebarDock) sidebarDock.classList.remove('dock-hidden');

                    const icon = newTrigger.querySelector('i');
                    if(icon) icon.classList.replace('ti-x', 'ti-menu-2');
                }
            });
            window.menuClickOutsideBound = true;
        }
    }
}

function checkWeatherWidget() {
    setTimeout(function() {
        const weatherId = 'ww_a063b4105b215'; 
        const $box = document.getElementById(weatherId);
        
        if ($box && ($box.children.length === 0 || $box.offsetHeight < 10)) {
            console.warn('天气加载失败，显示重试按钮');
            $box.innerHTML = `
                <div class="weather-retry-btn" onclick="window.retryWeather()" style="position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);text-align: center;cursor: pointer;color: #999;font-size: 13px;width: 100%;padding: 20px 0;">
                    <i class="ti ti-refresh" style="font-size: 24px;display: block;margin-bottom: 5px;"></i>
                    <span>加载失败<br>点击重试</span>
                </div>
            `;
        }
    }, 3000);
}

// =========================================
// 初始化执行
// =========================================
document.addEventListener('DOMContentLoaded', function() {
    window.reloadThemeComponents();
});