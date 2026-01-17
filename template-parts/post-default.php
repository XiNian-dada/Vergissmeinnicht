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
        <?php
            // 1. 计算天数
            $mtime = $this->modified;
            $ctime = $this->created;
            $days = floor((time() - $mtime) / 86400);

            // 2. 判断是否显示 (这里设置为 > -1 表示始终显示，你也可以改成 > 0)
            if ($days > -1):
                // 3. 判定时效等级
                if ($days <= 30) {
                    $level = 'new';
                    $text = '本文内容较新';
                    $icon = '✅';
                } elseif ($days <= 365) {
                    $level = 'medium';
                    $text = '本文有一定时效性';
                    $icon = '⚠️';
                } else {
                    $level = 'old';
                    $text = '本文内容可能已过时';
                    $icon = '⚠️';
                }

                // 4. 生成时间描述
                if ($days == 0) $ago = '今天更新';
                elseif ($days == 1) $ago = '昨天更新';
                elseif ($days < 30) $ago = $days . '天前更新';
                elseif ($days < 365) $ago = floor($days / 30) . '个月前更新';
                else $ago = floor($days / 365) . '年前更新';
        ?>
        <div class="lastupdate-box lastupdate-auto lastupdate-full-bg lastupdate-level-<?php echo $level; ?>">
            <span class="lastupdate-icon lastupdate-icon-<?php echo $level; ?>"><?php echo $icon; ?></span>
            <div class="lastupdate-text">
                <span class="lastupdate-level"><?php echo $text; ?></span>
                <span class="lastupdate-separator">·</span>
                <span class="lastupdate-time"><?php echo $ago; ?></span>
            </div>
            <div class="lastupdate-details">
                <span class="lastupdate-date">最后更新: <?php echo date('Y年m月d日', $mtime); ?></span>
            </div>
        </div>
        <?php endif; ?>
        <?php $this->content(); ?>
    </div>

    <div class="article-footer post-footer">
        <div class="tags">
            <i class="ti ti-hash"></i> <?php $this->tags(' ', true, '无标签'); ?>
        </div>
    </div>
</article>