<?php
/**
 * 文章详情页内容片段
 * 注意：这里不要引入 header/footer/sidebar，因为 post.php 已经引过了
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>

<article class="post-detail-card article-content" itemscope itemtype="http://schema.org/Article">
    
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