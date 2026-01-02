<?php use Widget\Contents\Related; if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php Related::alloc(['cid' => $this->cid, 'type' => $this->type, 'tags' => $this->tags, 'limit' => '5'])->to($relatedPosts); ?>
<?php if($relatedPosts->have()): ?>
<section class="glass-panel sidebar-section">
    <h3 class="section-title">
        <i class="ti ti-flame"></i> 相关文章
    </h3>
    <ul class="sidebar-list">
        <?php while ($relatedPosts->next()): ?>
            <li>
                <a href="<?php $relatedPosts->permalink(); ?>" title="<?php $relatedPosts->title(); ?>">
                    <?php $relatedPosts->title(); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</section>
<?php endif; ?>