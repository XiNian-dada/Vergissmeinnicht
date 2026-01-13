<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<aside class="sidebar-right" id="sidebar">
    
    <!-- 1. 静态区域：头像卡片 (不需要变) -->
    <div class="glass-panel profile-card mb-4">
        <!-- ... 原有的头像代码保持不变 ... -->
        <div class="profile-avatar">
            <?php if ($this->options->profileAvatar): ?>
                <img src="<?php $this->options->profileAvatar(); ?>" alt="Avatar">
            <?php else: ?>
                <img src="<?php $this->options->themeUrl('assets/img/default-avatar.png'); ?>" alt="Avatar">
            <?php endif; ?>
        </div>
        <h3 class="profile-name">
            <?php $this->options->profileName ? $this->options->profileName() : $this->options->title(); ?>
        </h3>
        <div class="profile-bio" style="min-height: 1.5em;">
            <span id="hitokoto_text" style="display: block; font-size: 14px; color: #777; line-height: 1.6;">
                <?php if ($this->options->profileBio): ?>
                    <?php $this->options->profileBio(); ?>
                <?php else: ?>
                    Typecho Developer
                <?php endif; ?>
            </span>
            
            <span id="hitokoto_from" style="display: block; font-size: 12px; transform: scale(0.9); opacity: 0.8; margin-top: 5px;"></span>

            <script>
            (function(){
                // c=d 代表文学，c=k 代表哲学
                fetch('https://v1.hitokoto.cn/?c=d&c=k')
                    .then(function(res){ return res.json(); })
                    .then(function(data) {
                        var hitokotoText = document.getElementById('hitokoto_text');
                        var hitokotoFrom = document.getElementById('hitokoto_from');
                        
                        // 更新正文
                        hitokotoText.innerText = data.hitokoto;
                        
                        // 更新来源 (例如：—— 《理想国》)
                        // 如果不需要来源，把下面这行删掉即可
                        if(hitokotoFrom && data.from) {
                            hitokotoFrom.innerText = "—— " + data.from;
                        }
                    })
                    .catch(function(err) {
                        console.error('一言加载失败，已回退到默认签名');
                    });
            })();
            </script>
        </div>
        <div class="profile-social">
            <!-- ... 社交链接代码保持不变 ... -->
             <?php if ($this->options->githubLink): ?>
                <a href="<?php $this->options->githubLink(); ?>" target="_blank" title="GitHub">
                    <i class="ti ti-brand-github"></i>
                </a>
            <?php endif; ?>
            <?php if ($this->options->bilibiliLink): ?>
                <a href="<?php $this->options->bilibiliLink(); ?>" target="_blank" title="Bilibili">
                    <i class="ti ti-brand-bilibili"></i>
                </a>
            <?php endif; ?>
            <?php if ($this->options->emailLink): ?>
                <a href="mailto:<?php $this->options->emailLink(); ?>" title="Email">
                    <i class="ti ti-mail"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="sticky-sidebar">
        <!-- 2. 静态部分：天气插件（绝对不刷新的部分） -->
        <section class="glass-panel sidebar-section weather-section">
            <div id="ww_9a31e1a9b6f4c" v='1.3' loc='auto' a='{"t":"horizontal","lang":"zh","sl_lpl":1,"ids":[],"font":"Arial","sl_ics":"one_a","sl_sot":"celsius","cl_bkg":"rgba(255,255,255,0)","cl_font":"rgba(0,0,0,0.8)","cl_cloud":"#ebebeb","cl_persp":"#2196F3","cl_sun":"#FFC107","cl_moon":"#FFC107","cl_thund":"#FF5722"}'>
                <a href="https://weatherwidget.org/zh/" id="ww_9a31e1a9b6f4c_u" target="_blank">天气插件</a>
            </div>
            <script async src="https://app3.weatherwidget.org/js/?id=ww_9a31e1a9b6f4c"></script>
        </section>

        <!-- 3. 动态部分：必须用 ID 包裹 -->
        <!-- JS 会抓取新页面的这个 ID 内部的内容，替换到当前页面 -->
        <div id="sidebar-dynamic-content">
            <?php $this->need('template-parts/right-hot-post.php'); ?>
            
            <?php $this->need('template-parts/right-latest-comment.php'); ?>
            
            <?php if($this->is('post')): ?>
                <?php $this->need('template-parts/right-related-post.php'); ?>
            <?php endif; ?>
            
            <?php $this->need('template-parts/right-hot-tag.php'); ?>
            
            <?php $this->need('template-parts/right-link.php'); ?>
        </div>
        
    </div>
</aside>