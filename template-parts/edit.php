<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php if($this->user->hasLogin()): ?>
    <?php if($this->is('post') || $this->is('page')): ?>
        <a href="<?php $this->options->adminUrl(); ?>write-<?php echo $this->is('post')?'post':'page'; ?>.php?cid=<?php echo $this->cid;?>" 
           class="edit-link" target="_blank">
            <i class="ti ti-edit"></i> 编辑
        </a>
    <?php endif; ?>
<?php endif; ?>