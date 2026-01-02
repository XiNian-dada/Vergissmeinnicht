<?php use widget\TopUpContent; if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php if ($this->is('index') && $this->_currentPage == 1): ?>
    <?php TopUpContent::alloc()->to($topUpPost); ?>
    <?php while ($topUpPost->next()): ?>
        <article class="glass-card featured-card mb-4" itemscope itemtype="http://schema.org/Article">
            <div class="featured-badge">
                <i class="ti ti-pin-filled"></i> 置顶
            </div>
            
            <a href="<?php $topUpPost->permalink(); ?>" class="card-link-mask"></a>

            <div class="card-content">
                <h2 class="card-title" itemprop="headline">
                    <?php $topUpPost->title(); ?>
                </h2>
                <div class="card-meta">
                    <span><?php $topUpPost->date(); ?></span>
                    <span><?php $topUpPost->category(' / '); ?></span>
                </div>
                <div class="card-excerpt">
                    <?php $topUpPost->excerpt(100, '...'); ?>
                </div>
            </div>

            <?php if ($topUpPost->fields->thumbnail): ?>
            <div class="card-bg-image" style="background-image: url('<?php $topUpPost->fields->thumbnail(); ?>')"></div>
            <?php endif; ?>
        </article>
    <?php endwhile; ?>
<?php endif; ?>