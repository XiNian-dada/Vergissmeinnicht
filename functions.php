<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

// 引入必要的 Widget
require_once "widget/HotPost.php";
require_once "widget/TopUpContent.php";
require_once "widget/CategorySub.php";

function themeConfig($form) {
    
    // ==========================================
    // 1. 基础设置 (Logo, SEO 等)
    // ==========================================
    
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        "logoUrl",
        null,
        null,
        _t("网站 Logo"),
        _t("在这里填写图片 URL，网站将显示 Logo")
    );
    $form->addInput($logoUrl->addRule("url", _t("请填写正确的 URL 地址")));
    
    $categoryNum = new \Typecho\Widget\Helper\Form\Element\Text(
        "categoryNum",
        null,
        5,
        _t("显示分类的数量"),
        _t("显示分类的数量，超过填写数字将不显示")
    );
    $form->addInput($categoryNum);

    // ==========================================
    // 2. 侧边栏个人卡片设置 (新增)
    // ==========================================
    
    $profileAvatar = new \Typecho\Widget\Helper\Form\Element\Text(
        'profileAvatar',
        null,
        null,
        _t('个人卡片-头像 URL'),
        _t('侧边栏个人展示卡片的头像地址（建议正方形）')
    );
    $form->addInput($profileAvatar);

    $profileName = new \Typecho\Widget\Helper\Form\Element\Text(
        'profileName',
        null,
        null,
        _t('个人卡片-昵称'),
        _t('侧边栏显示的名称')
    );
    $form->addInput($profileName);

    $profileBio = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'profileBio',
        null,
        null,
        _t('个人卡片-简介'),
        _t('一段简短的自我介绍')
    );
    $form->addInput($profileBio);

    $githubLink = new \Typecho\Widget\Helper\Form\Element\Text(
        'githubLink',
        null,
        null,
        _t('GitHub 链接'),
        _t('填写您的 GitHub 主页链接，留空则不显示')
    );
    $form->addInput($githubLink);

    $bilibiliLink = new \Typecho\Widget\Helper\Form\Element\Text(
        'bilibiliLink',
        null,
        null,
        _t('Bilibili 链接'),
        _t('填写您的 B 站主页链接，留空则不显示')
    );
    $form->addInput($bilibiliLink);
    
    $emailLink = new \Typecho\Widget\Helper\Form\Element\Text(
        'emailLink',
        null,
        null,
        _t('联系邮箱'),
        _t('填写您的联系邮箱，留空则不显示')
    );
    $form->addInput($emailLink);

    // ==========================================
    // 3. 视觉与光效设置
    // ==========================================
    
    // 背景效果类型
    $backgroundType = new \Typecho\Widget\Helper\Form\Element\Radio(
        'backgroundType',
        array(
            'none' => _t('无背景效果'),
            'lights' => _t('动态光效'),
            'image' => _t('静态图片')
        ),
        'lights',
        _t('背景效果'),
        _t('选择网站的背景效果类型')
    );
    $form->addInput($backgroundType);
    
    // 背景图片URL
    $backgroundImage = new \Typecho\Widget\Helper\Form\Element\Text(
        'backgroundImage',
        null,
        null,
        _t('背景图片地址'),
        _t('当背景效果选择"静态图片"时，在此填写图片URL')
    );
    $form->addInput($backgroundImage);
    
    // 光效强度
    $lightIntensity = new \Typecho\Widget\Helper\Form\Element\Radio(
        'lightIntensity',
        array(
            'low' => _t('低强度（性能优先）'),
            'normal' => _t('正常强度'),
            'high' => _t('高强度（效果优先）')
        ),
        'normal',
        _t('光效强度'),
        _t('调整光效的显示强度')
    );
    $form->addInput($lightIntensity);
    
    // 光效颜色主题
    $lightColorScheme = new \Typecho\Widget\Helper\Form\Element\Radio(
        'lightColorScheme',
        array(
            'auto' => _t('自动（跟随主题）'),
            'warm' => _t('始终暖色'),
            'cool' => _t('始终冷色'),
            'custom' => _t('自定义')
        ),
        'auto',
        _t('光效配色'),
        _t('选择光效的颜色方案')
    );
    $form->addInput($lightColorScheme);
    
    // 自定义颜色
    $customLightColors = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'customLightColors',
        null,
        '{"primary": "rgba(100, 149, 237, 0.6)", "secondary": "rgba(70, 130, 180, 0.3)"}',
        _t('自定义光效颜色'),
        _t('JSON格式的颜色配置')
    );
    $form->addInput($customLightColors);

    // ==========================================
    // 4. 高级代码注入
    // ==========================================
    
    $customHead = new \Typecho\Widget\Helper\Form\Element\Textarea(
        "customHead",
        null,
        null,
        _t("Head 代码"),
        _t("自定义注入 head 区域的代码（如统计代码、自定义 CSS）")
    );
    $form->addInput($customHead);
    
    $customFooter = new \Typecho\Widget\Helper\Form\Element\Textarea(
        "customFooter",
        null,
        null,
        _t("Footer 代码"),
        _t("自定义注入 footer 区域的代码（如 JS 脚本）")
    );
    $form->addInput($customFooter);
}

function themeFields($layout) {
    // 删除了 file_get_contents 检查，直接加载 JS
    echo "<script src=\"" . \Typecho\Common::url("assets/main/admin.js", \Utils\Helper::options()->themeUrl) . "\"></script>";
    
    $topUp = new \Typecho\Widget\Helper\Form\Element\Radio(
        "topUp",
        array("1" => _t("是"), "0" => _t("否")),
        0,
        _t("首页置顶"),
        _t("是：置顶；否：不置顶")
    );
    $layout->addItem($topUp);
    
    $postType = new \Typecho\Widget\Helper\Form\Element\Radio(
        "postType",
        array("1" => _t("标准"), "2" => _t("阅读")),
        1,
        _t("<span class=\"removeByPage\">文章类型</span>"),
        _t('')
    );
    $layout->addItem($postType);
    
    $showToc = new \Typecho\Widget\Helper\Form\Element\Radio(
        "showToc",
        array("1" => _t("显示"), "0" => _t("隐藏")),
        0,
        _t("文章目录"),
        _t('')
    );
    $layout->addItem($showToc);
    
    $thumbnail = new \Typecho\Widget\Helper\Form\Element\Text(
        "thumbnail",
        null,
        null,
        _t("缩略图"),
        _t("填写图片地址")
    );
    $layout->addItem($thumbnail);
    
    $showPage = new \Typecho\Widget\Helper\Form\Element\Radio(
        "showPage",
        array("1" => _t("是"), "0" => _t("否")),
        0,
        _t("<span class=\"removeByPost\">在左侧显示</span>"),
        _t("是：页面将在左侧显示；否：隐藏")
    );
    $layout->addItem($showPage);
    
    $iconPage = new \Typecho\Widget\Helper\Form\Element\Text(
        "iconPage",
        null,
        null,
        _t("<span class=\"removeByPost\">左侧显示内容</span>"),
        _t("填入html代码，可显示为图片、图标等。此内容由“在左侧显示”选项控制")
    );
    $layout->addItem($iconPage);
}

function postNavbarActive($archive, $slug) {
    if ($archive->is("post")) {
        $categories = $archive->categories;
        foreach ($categories as $category) {
            if ($category["slug"] === $slug) {
                return "active";
            }
        }
    }
    return '';
}

function threadedComments($comments, $options) {
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= " comment-by-author";
        } else {
            $commentClass .= " comment-by-user";
        }
    } 
    ?>
    
    <li id="<?php $comments->theId(); ?>" class="comment-body<?php 
        if ($comments->levels > 0) {
            echo ' comment-child';
            $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
        } else {
            echo ' comment-parent';
        }
        $comments->alt(' comment-odd', ' comment-even');
        echo $commentClass;
    ?>">
        
        <div class="glass-panel comment-card p-3 mb-3 position-relative">
            <div class="d-flex column-gap-3">
                
                <div class="comment-avatar">
                    <?php $comments->gravatar('48', $options->defaultAvatar, $options->avatarHighRes); ?>
                </div>

                <div class="flex-fill">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="comment-info">
                            <span class="fw-bold text-main me-2"><?php $comments->author(); ?></span>
                            <?php if ($comments->authorId == $comments->ownerId): ?>
                                <span class="badge rounded-pill bg-primary text-white" style="font-size: 10px; padding: 2px 6px;">博主</span>
                            <?php endif; ?>
                            
                            <span class="text-secondary small ms-2">
                                <?php $comments->date('Y-m-d H:i'); ?>
                            </span>
                        </div>
                        
                        <div class="comment-reply">
                            <?php $comments->reply('<i class="ti ti-arrow-back-up"></i> 回复'); ?>
                        </div>
                    </div>
                    
                    <div class="comment-content text-break typo" style="font-size: 14px;">
                        <?php $comments->content(); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($comments->children) { ?>
            <div class="comment-children ps-4"> <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </li>
<?php }