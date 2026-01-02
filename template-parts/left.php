<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<aside class="sidebar-dock glass-panel">
    
    <div class="dock-content">
        <a href="<?php $this->options->siteUrl(); ?>" class="dock-item" title="首页">
            <i class="ti ti-home"></i>
        </a>

        <?php Widget\Contents\Page\Rows::alloc()->to($pages); ?>
        <?php while ($pages->next()): ?>
            <?php if ($pages->fields->showPage): ?>
                <a href="<?php $pages->permalink() ?>" class="dock-item" title="<?php $pages->title() ?>">
                    <i class="ti ti-<?php echo $pages->fields->iconPage ?: 'file'; ?>"></i>
                </a>
            <?php endif; ?>
        <?php endwhile; ?>

        <div class="dock-divider"></div>
        <a href="javascript:;" id="weather-toggle" class="dock-item" title="切换天气">
            <i class="ti ti-cloud-off"></i>
        </a>

        <a href="javascript:;" id="theme-toggle" class="dock-item" title="切换模式">
            <?php 
                // 获取当前模式，决定显示什么图标
                // 如果 cookie 是 dark，显示月亮，否则显示太阳
                $currentMode = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
                $iconClass = ($currentMode === 'dark') ? 'ti-moon' : 'ti-sun';
            ?>
            <i class="ti <?php echo $iconClass; ?>"></i>
        </a>

        <a href="#top" class="dock-item" title="回到顶部">
            <i class="ti ti-arrow-bar-to-up"></i>
        </a>
    </div>

</aside>