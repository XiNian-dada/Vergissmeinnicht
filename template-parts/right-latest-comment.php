<?php use Widget\Comments\Recent; if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<section class="glass-panel sidebar-section">
    <h3 class="section-title">
        <i class="ti ti-message"></i> 最新评论
    </h3>
    <ul class="sidebar-list comment-list-sidebar">
        <?php Recent::alloc(["pageSize" => "7", "ignoreAuthor" => "1"])->to($comments); ?>
        <?php while ($comments->next()): ?>
            <li>
                <a href="<?php $comments->permalink(); ?>" class="comment-link">
                    <span class="author"><?php $comments->author(false); ?></span>
                    <span class="summary"> : <?php $comments->excerpt(20, '...'); ?></span>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</section>