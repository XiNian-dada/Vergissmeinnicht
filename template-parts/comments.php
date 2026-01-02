<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<div id="comments" class="glass-panel comments-section">
    
    <div class="comments-header">
        <h3 class="section-title">
            评论 <span class="count"><?php $this->commentsNum(_t('暂无'), _t('1 条'), _t('%d 条')); ?></span>
        </h3>
    </div>

    <?php $this->comments()->to($comments); ?>

    <?php if ($this->allow('comment')): ?>
    <div id="<?php $this->respondId(); ?>" class="respond">
        
        <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" class="comment-form">
            
            <?php if (!$this->user->hasLogin()): ?>
                <div class="form-row">
                    <input type="text" name="author" class="glass-input" placeholder="昵称 (可选)" value="<?php $this->remember('author'); ?>">
                    
                    <input type="email" name="mail" class="glass-input" placeholder="邮箱 (必填) *" value="<?php $this->remember('mail'); ?>" required>
                    
                    <input type="url" name="url" class="glass-input" placeholder="网站 (http://...)" value="<?php $this->remember('url'); ?>">
                </div>
            <?php endif; ?>

            <div class="form-group">
                <textarea rows="6" name="text" class="glass-input textarea-large" placeholder="发表你的看法..." required><?php $this->remember('text'); ?></textarea>
            </div>

            <div class="turnstile-box">
                <?php Turnstile_Plugin::output(); ?>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn-glass primary">提交评论</button>
            </div>
            
        </form>
    </div>
    <?php else: ?>
        <div class="comments-closed">
            <h4 style="text-align:center; color:var(--text-secondary); margin: 20px 0;">评论功能已关闭</h4>
        </div>
    <?php endif; ?>

    <?php if ($comments->have()): ?>
        
        <div class="comment-separator"></div> 
        
        <div class="comment-list-wrapper mt-5"> 
            <h4 class="mb-4 text-secondary" style="font-size: 16px;">
                已有 <?php $this->commentsNum(_t('0'), _t('1'), _t('%d')); ?> 条评论
            </h4>
            
            <?php $comments->listComments(); ?>
        </div>

        <div class="pagination">
            <?php $comments->pageNav('&laquo;', '&raquo;'); ?>
        </div>
        
    <?php endif; ?>
    
</div>