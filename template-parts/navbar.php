<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<header class="glass-header sticky-top">
    <div class="header-inner">
        <div class="header-left">
            <button class="icon-btn mobile-menu-trigger" id="menu-trigger">
                <i class="ti ti-menu-2"></i>
            </button>
            <a href="<?php $this->options->siteUrl(); ?>" class="logo-link">
                <?php if ($this->options->logoUrl): ?>
                    <img src="<?php $this->options->logoUrl(); ?>" alt="Logo" class="logo-img">
                <?php else: ?>
                    <span class="logo-text fw-bold"><?php $this->options->title() ?></span>
                <?php endif; ?>
            </a>
        </div>

        <nav class="desktop-nav" id="mobile-drawer">
            <a href="<?php $this->options->siteUrl(); ?>" class="nav-link <?php echo $this->is('index') ? 'active' : '' ?>">首页</a>
            
            <?php Widget\Metas\Category\Rows::alloc()->to($categories); ?>
            <?php while ($categories->next()): ?>
                <?php if ($categories->parent == 0): ?>
                    <a href="<?php $categories->permalink(); ?>" class="nav-link <?php echo ($this->is('category', $categories->slug)) ? 'active' : '' ?>">
                        <?php $categories->name(); ?>
                    </a>
                <?php endif; ?>
            <?php endwhile; ?>
        
            <div class="mobile-only-search">
                <form method="post" action="">
                    <input type="text" name="s" placeholder="输入关键字搜索...">
                    <button type="submit"><i class="ti ti-search"></i></button>
                </form>
            </div>
        </nav>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var trigger = document.getElementById('menu-trigger');
            var drawer = document.getElementById('mobile-drawer');
            
            if(trigger && drawer) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault(); // 防止跳转
                    drawer.classList.toggle('active'); // 切换 active 类
                });
            }
        });
        </script>

        <div class="header-right">
            <form method="post" action="" class="search-form">
                <i class="ti ti-search search-icon"></i>
                <input type="text" name="s" class="search-input" placeholder="搜索...">
            </form>
        </div>
    </div>
</header>