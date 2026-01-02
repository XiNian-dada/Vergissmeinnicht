<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<article class="glass-card post-item" itemscope itemtype="http://schema.org/Article">
    <a href="<?php $this->permalink(); ?>" class="card-link-mask"></a>
    
    <div class="post-content">
        <header class="post-header">
            <div class="post-meta">
                <span class="meta-item date"><?php $this->date(); ?></span>
                <span class="meta-item category"><?php $this->category(' / '); ?></span>
            </div>
            <h2 class="post-title" itemprop="headline">
                <?php $this->title(); ?>
            </h2>
        </header>
        
        <div class="post-excerpt" itemprop="about">
            <?php $this->excerpt(120, '...'); ?>
        </div>
    </div>

    <?php if ($this->fields->thumbnail): ?>
        <div class="post-thumbnail">
            <img src="<?php $this->fields->thumbnail(); ?>" alt="<?php $this->title(); ?>" loading="lazy">
        </div>
    <?php endif; ?>
</article>