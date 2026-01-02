<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<section class="glass-panel sidebar-section">
    <h3 class="section-title">
        <i class="ti ti-chart-bar"></i> 热门文章
    </h3>
    <ul class="sidebar-list">
        <?php widget\HotPost::alloc()->to($hotPosts) ?>
        <?php while ($hotPosts->next()): ?>
            <li>
                <a href="<?php $hotPosts->permalink(); ?>" title="<?php $hotPosts->title(); ?>">
                    <span class="text"><?php $hotPosts->title(); ?></span>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</section>