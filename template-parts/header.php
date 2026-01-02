<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
// 1. PHP 读取 Cookie
$themeMode = 'light'; // 默认浅色
if (isset($_COOKIE['theme']) && in_array($_COOKIE['theme'], ['light', 'dark'])) {
    $themeMode = $_COOKIE['theme'];
}
?>
<!DOCTYPE html>
<html lang="zh-Hans" data-bs-theme="<?php echo $themeMode; ?>">
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php $this->archiveTitle('', '', ' | '); ?><?php $this->options->title(); ?></title>
    
    <meta name="mobile-web-app-capable" content="yes"> <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">

    <link href="<?php $this->options->themeUrl('assets/main/main.css'); ?>" rel="stylesheet">
    <link href="<?php $this->options->themeUrl('assets/main/prism.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <?php if ($themeMode === 'dark'): ?>
    <style id="dark-mode-critical-css">
        html, body, .app-background { 
            background-color: #000000 !important; 
            color: #f5f5f7 !important;
        }
        * { transition: none !important; }
    </style>
    <?php endif; ?>

    <script>
        (function() {
            function setCookie(name, value, days) {
                var expires = "";
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "") + expires + "; path=/; SameSite=Lax";
            }
    
            var html = document.documentElement;
            var currentTheme = html.getAttribute('data-bs-theme'); 
            
            if (currentTheme) {
                localStorage.setItem('theme', currentTheme);
            }
    
            document.addEventListener('click', function(e) {
                var toggleBtn = e.target.closest('#theme-toggle');
                if (toggleBtn) {
                    e.preventDefault(); 
                    var currentNow = html.getAttribute('data-bs-theme');
                    var newTheme = currentNow === 'dark' ? 'light' : 'dark';
                    html.setAttribute('data-bs-theme', newTheme);
                    var icon = toggleBtn.querySelector('i');
                    if (icon) {
                        icon.className = 'ti ' + (newTheme === 'dark' ? 'ti-moon' : 'ti-sun');
                    }
                    setCookie('theme', newTheme, 365);
                    localStorage.setItem('theme', newTheme);
                }
            });
    
            window.addEventListener('load', function() {
                setTimeout(function(){
                    var criticalStyle = document.getElementById('dark-mode-critical-css');
                    if (criticalStyle) {
                        criticalStyle.remove();
                    }
                }, 0);
            });
        })();
    </script>
    
    <?php $this->header('generator=&template=&pingback=&xmlrpc=&wlw='); ?>
    <?php $this->options->customHead(); ?>
</head>
<body> <div id="weather-container">
    <canvas id="weather-canvas"></canvas>
</div>

<div class="app-background"></div>

<div class="app-container">