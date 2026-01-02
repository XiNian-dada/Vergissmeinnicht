<?php use Typecho\Common; if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<article class="glass-panel moment-detail mb-4">
    <div class="moment-header">
        <img src="<?php echo Common::gravatarUrl($this->author->mail, 50, 'X', 'mm', $this->request->isSecure()) ?>" 
             alt="<?php $this->author(); ?>" class="avatar">
        <div class="moment-info">
            <span class="author-name"><?php $this->author(); ?></span>
            <span class="time"><?php $this->date(); ?></span>
        </div>
    </div>
    
    <div class="moment-body typo">
        <?php $this->content(); ?>
    </div>
</article>