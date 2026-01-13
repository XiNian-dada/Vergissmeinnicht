<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<article class="post-detail-card article-content" itemscope itemtype="http://schema.org/Article">
    
    <?php if ($this->fields->showToc == '1'): ?>
    <div id="toc-grid-source" style="display:none;">
        <div class="toc-container glass-panel">
            <h3 class="section-title">
                <i class="ti ti-list"></i> 目录
            </h3>
            <div id="toc-content" class="toc-list">
                </div>
        </div>
    </div>
    <?php endif; ?>

    <header class="post-title-box article-header">
        <h1 class="article-title" itemprop="headline"><?php $this->title(); ?></h1>
        
        <div class="post-meta-data text-secondary">
            <span><i class="ti ti-calendar"></i> <time datetime="<?php $this->date('c'); ?>"><?php $this->date(); ?></time></span>
            <span><i class="ti ti-folder"></i> <?php $this->category(', '); ?></span>
            
            <?php $this->need('template-parts/edit.php'); ?>
        </div>
    </header>

    <div class="article-body typo" itemprop="articleBody">
        <?php $this->content(); ?>
    </div>

    <div class="article-footer post-footer">
        <div class="tags">
            <i class="ti ti-hash"></i> <?php $this->tags(' ', true, '无标签'); ?>
        </div>
    </div>
</article>