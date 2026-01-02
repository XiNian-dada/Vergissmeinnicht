<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<article class="glass-card moment-item">
    <div class="moment-header">
        <img src="<?php echo Typecho_Common::gravatarUrl($this->author->mail, 48, 'X', 'mm', $this->request->isSecure()) ?>" 
             class="avatar" alt="Avatar">
        <div class="moment-meta">
            <span class="author-name"><?php $this->author(); ?></span>
            <time class="post-date"><?php $this->date('m-d H:i'); ?></time>
        </div>
    </div>
    <div class="moment-content">
        <?php $this->excerpt(200, ''); ?>
    </div>
    <a href="<?php $this->permalink(); ?>" class="moment-link-mask"></a>
</article>