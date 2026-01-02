<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

</div> <footer class="glass-footer">
    <div class="footer-grid">
        <div class="footer-col info">
            <h3><?php $this->options->title(); ?></h3>
            <p>&copy; <?php echo date('Y'); ?> All Rights Reserved.</p>
        </div>

        <div class="footer-col stats">
            <div class="stat-box">
                <span class="label">文章</span>
                <?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?>
                <span class="value"><?php $stat->publishedPostsNum() ?></span>
            </div>
            <div class="stat-box">
                <span class="label">评论</span>
                <span class="value"><?php $stat->publishedCommentsNum() ?></span>
            </div>
        </div>

        <div class="footer-col links">
            <a href="<?php $this->options->feedUrl(); ?>" target="_blank"><i class="ti ti-rss"></i> RSS</a>
            <a href="http://typecho.org" target="_blank">Typecho</a>
        </div>
    </div>
</footer>

<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js"></script>
<script src="<?php $this->options->themeUrl('assets/main/weather.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/main/prism.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('assets/main/main.js'); ?>"></script>

<script>
// 检查 jQuery 是否正常加载
if (typeof jQuery === 'undefined') {
    alert('错误：jQuery 未加载！');
}

$(document).ready(function() {
    console.log('--- PJAX 初始化 ---');

    // ★★★ 核心修复：不依赖 siteUrl 字符串匹配，改为域名匹配 ★★★
    // 这样可以完美解决 http/https 或 www 带来的匹配失败问题
    $(document).pjax(
        'a[href]:not([target="_blank"]):not([no-pjax]):not([href^="#"]):not(.no-pjax)', 
        {
            container: '#middle',
            fragment: '#middle',
            timeout: 8000
        }
    );

    // 额外的过滤器：确保只处理同域名的链接，且排除特定区域
    $(document).on('click', 'a[href]', function(event) {
        var link = this;
        
        // 1. 跨域链接不处理
        if (link.hostname !== location.hostname) return;

        // 2. 排除特定 class 或区域
        if ($(link).hasClass('nav-link') || // 导航栏链接 (如果你想导航栏也无刷新，请去掉这一行)
            $(link).closest('.sidebar-section').length || 
            $(link).closest('.glass-footer').length || 
            $(link).closest('.profile-card').length ||
            link.href.indexOf('comment') > -1 ||
            link.href.indexOf('feed') > -1 ||
            link.href.indexOf('action') > -1) {
            return; // 让浏览器执行默认跳转
        }
        
        // 如果能执行到这里，说明是合法的 PJAX 链接，pjax 库会自动接管
    });

    // PJAX 开始动画
    $(document).on('pjax:send', function() {
        $('#middle').fadeTo(200, 0.5);
    });

    // PJAX 完成回调
    $(document).on('pjax:complete', function(event, xhr, options) {
        console.log('PJAX: 完成');
        $('#middle').fadeTo(200, 1);
        
        if (xhr && xhr.responseText) {
            // 解析返回的 HTML
            var doc = new DOMParser().parseFromString(xhr.responseText, 'text/html');
            
            // 1. 更新侧边栏动态内容 (如果有)
            var newSidebarContent = doc.getElementById('sidebar-dynamic-content');
            if (newSidebarContent) {
                $('#sidebar-dynamic-content').html(newSidebarContent.innerHTML);
            }
            // 2. 更新标题
            document.title = doc.title;
        }

        // 3. 重载组件 (天气、代码高亮等)
        if (typeof window.reloadThemeComponents === 'function') {
            window.reloadThemeComponents();
        }
        
        // 4. 统计代码
        if (typeof _hmt !== 'undefined') {
            _hmt.push(['_trackPageview', location.pathname + location.search]);
        }
    });

    // 错误处理
    $(document).on('pjax:error', function(event, xhr, textStatus, error, options) {
        console.error('PJAX Error:', textStatus, error);
        // 返回 false 禁止硬刷新，方便你在控制台看报错
        // 调试完成后，可以把这行注释掉，让它自动回退到硬刷新
        // return false; 
    });
});
</script>
</body>
</html>