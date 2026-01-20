<?php use widget\TopUpContent; if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php if ($this->is('index') && $this->_currentPage == 1): ?>
    <?php TopUpContent::alloc()->to($topUpPost); ?>
    <?php while ($topUpPost->next()): ?>
        <article class="glass-card featured-card mb-4" itemscope itemtype="http://schema.org/Article">
            <div class="featured-badge">
                <i class="ti ti-pin-filled"></i> 置顶
            </div>
            
            <?php
            // 初始化目标链接
            $targetUrl = '#';

            // 1. 尝试获取文章 ID (cid)
            $pId = $topUpPost->cid;

            // 如果 ID 存在，我们绕过 TopUpContent，直接找系统要链接
            if (!empty($pId)) {
                $db = Typecho_Db::get();
                $options = Typecho_Widget::widget('Widget_Options');
                
                $nativePost = $db->fetchRow($db->select()->from('table.contents')->where('cid = ?', $pId));
                
                if ($nativePost) {
                    $nativePost['year'] = date('Y', $nativePost['created']);
                    $nativePost['month'] = date('m', $nativePost['created']);
                    $nativePost['day'] = date('d', $nativePost['created']);
                    
                    // 强制指定类型为 post 
                    $nativePost['type'] = 'post';

                    try {
                        // 使用系统路由器生成标准链接
                        $targetUrl = Typecho_Router::url('post', $nativePost, $options->index);
                    } catch (Exception $e) {
                        // 路由失败忽略
                    }
                }
            }

            if ($targetUrl == '#' && !empty($pId)) {
                $siteUrl = Typecho_Widget::widget('Widget_Options')->siteUrl;
                if (substr($siteUrl, -1) != '/') $siteUrl .= '/'; // 确保网址后面有 /
                $targetUrl = $siteUrl . '?p=' . $pId;
            }
            ?>
            
            <a href="<?php echo $targetUrl; ?>" class="card-link-mask"></a>

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