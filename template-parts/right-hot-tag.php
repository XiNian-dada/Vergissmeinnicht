<?php use Widget\Metas\Tag\Cloud; if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<section class="glass-panel sidebar-section">
    <h3 class="section-title">
        <i class="ti ti-tags"></i> 热门标签
    </h3>
    <div class="tags-cloud">
        <?php Cloud::alloc(["ignoreZeroCount" => "1", "limit" => "20", "sort" => "count", "desc" => "1"])->to($tags) ?>
        <?php while ($tags->next()): ?>
            <a href="<?php $tags->permalink(); ?>" class="tag-item" title="<?php $tags->count(); ?> 篇">
                <?php $tags->name(); ?>
            </a>
        <?php endwhile; ?>
    </div>
</section>