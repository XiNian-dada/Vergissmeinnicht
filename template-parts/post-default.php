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
        // ========================================
        // 1. 最后更新时间提示
        // ========================================
        $mtime = $this->modified;
        $ctime = $this->created;
        $days = floor((time() - $mtime) / 86400);
        
        if ($days > -1):
            // 判定时效等级
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
            
            // 生成时间描述
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

        <?php
        // ========================================
        // 2. 阅读时间估计
        // ========================================
        // 配置参数
        $readingSpeed = 250; // 每分钟阅读字数
        $imageReadingTime = 0.5; // 每张图片增加的时间（分钟）
        
        // 清理内容并计算字数
        $cleanContent = strip_tags($this->content);
        $cleanContent = preg_replace('/\[[^\]]+\]/', '', $cleanContent);
        $cleanContent = preg_replace('/\s+/', ' ', $cleanContent);
        $wordCount = mb_strlen(trim($cleanContent), 'UTF-8');
        
        // 计算图片数量
        preg_match_all('/<img[^>]+>/i', $this->content, $imgMatches);
        preg_match_all('/!\[[^\]]*\]\([^\)]+\)/i', $this->content, $mdImgMatches);
        $imageCount = count($imgMatches[0]) + count($mdImgMatches[0]);
        
        // 计算总阅读时间
        $textReadingTime = $wordCount / $readingSpeed;
        $imageTime = $imageCount * $imageReadingTime;
        $totalReadingTime = $textReadingTime + $imageTime;
        
        // 格式化显示时间
        if ($totalReadingTime < 1) {
            $displayTime = '1';
        } else {
            $displayTime = ($totalReadingTime == floor($totalReadingTime)) 
                ? (string)floor($totalReadingTime) 
                : number_format($totalReadingTime, 1);
        }
        ?>
        <div class="reading-time-box reading-time-auto reading-time-full-bg">
            <span class="reading-time-icon">⏱</span>
            <div class="reading-time-text">
                预计阅读时间： <span class="reading-time-minutes"><?php echo $displayTime; ?></span> 分钟
            </div>
            <div class="reading-time-details">
                <span class="reading-time-words"><?php echo $wordCount; ?> 字</span>
                <?php if ($imageCount > 0): ?>
                <span class="reading-time-images"><?php echo $imageCount; ?> 图</span>
                <?php endif; ?>
                <span class="reading-time-speed"><?php echo $readingSpeed; ?> 字/分</span>
            </div>
        </div>

        <?php $this->content(); ?>
    </div>

    <div class="article-footer post-footer">
        <div class="tags">
            <i class="ti ti-hash"></i> <?php $this->tags(' ', true, '无标签'); ?>
        </div>
    </div>
</article>