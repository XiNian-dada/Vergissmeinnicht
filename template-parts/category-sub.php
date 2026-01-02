<?php use widget\CategorySub; if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php if($this->pageRow['mid']): ?>
    <?php CategorySub::alloc(['parent' => $this->pageRow['mid']])->to($categories) ?>
    <?php if ($categories->have()): ?>
        <div class="sub-category-nav">
            <a class="cat-pill active" href="<?php $this->archiveUrl(); ?>">全部</a>
            <?php while ($categories->next()): ?>
                <a class="cat-pill" href="<?php $categories->permalink(); ?>">
                    <?php $categories->name(); ?>
                </a>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>