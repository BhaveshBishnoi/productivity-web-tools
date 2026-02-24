<?php
require_once 'lang.php';

// Get saved settings or defaults
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'dark';
$content = isset($_SESSION['notepad_content']) ? $_SESSION['notepad_content'] : '';
$font_family = isset($_SESSION['font_family']) ? $_SESSION['font_family'] : 'Inter';
$font_size = isset($_SESSION['font_size']) ? $_SESSION['font_size'] : '16px';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['title']; ?></title>
    <link rel="stylesheet" href="style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="alternate icon" href="favicon.ico">
    
    <!-- Meta for SEO -->
    <meta name="description" content="<?php echo $t['meta_description']; ?>">
    <meta name="keywords" content="<?php echo $t['meta_keywords']; ?>">
    <meta name="author" content="Notepad App">
    
    <!-- Open Graph / Social -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $t['title']; ?>">
    <meta property="og:description" content="<?php echo $t['meta_description']; ?>">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
    <meta property="og:image" content="favicon.svg">
    
    <!-- PWA capable -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
</head>
<body>
    <div class="nav-dock-trigger"></div>
    <!-- Floating Navigation Dock -->
    <nav class="nav-dock">
        <div class="nav-left">
            <div class="logo">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                <span class="logo-text"><?php echo $t['title']; ?></span>
            </div>
        </div>

        <div class="nav-center">
             <!-- File Controls -->
            <button class="icon-btn" id="btn-new" aria-label="<?php echo $t['new']; ?>" title="<?php echo $t['new']; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            </button>
            <button class="icon-btn" id="btn-save" aria-label="<?php echo $t['save']; ?>" title="<?php echo $t['save']; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
            </button>
            <button class="icon-btn" id="btn-open" aria-label="<?php echo $t['open']; ?>" title="<?php echo $t['open']; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                <!-- Using different icon for open/upload for style -->
                <svg style="display:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 13h6m-3-3v6m5 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"></path></svg>
            </button>
            <input type="file" id="file-input" style="display: none;" accept=".txt,.md,.html,.json">
            
            <div class="divider"></div>

            <!-- Styling Controls -->
            <div class="divider"></div>
            
            <!-- View Controls -->
            <button class="icon-btn" id="btn-view-mode" title="Toggle Narrow View">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="12" height="16" rx="2" /></svg>
            </button>
            <button class="icon-btn" id="btn-focus" title="Focus Mode">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
            </button>
            
            <div class="divider"></div>

            <div class="select-wrapper">
                <select id="font-family" title="<?php echo $t['font_family']; ?>">
                    <option value="Inter" <?php echo $font_family === 'Inter' ? 'selected' : ''; ?>>Sans</option>
                    <option value="'Playfair Display', serif" <?php echo strpos($font_family, 'Playfair') !== false ? 'selected' : ''; ?>>Serif</option>
                    <option value="'JetBrains Mono', monospace" <?php echo strpos($font_family, 'Mono') !== false ? 'selected' : ''; ?>>Mono</option>
                </select>
            </div>
            
            <div class="select-wrapper small">
                <select id="font-size" title="<?php echo $t['font_size']; ?>">
                    <?php
                    $sizes = ['12px' => '12', '14px' => '14', '16px' => '16', '18px' => '18', '24px' => '24', '32px' => '32'];
                    foreach ($sizes as $val => $label) {
                        $sel = $font_size === $val ? 'selected' : '';
                        echo "<option value=\"$val\" $sel>$label</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="nav-right">
             <div class="select-wrapper lang-wrapper">
                <select id="lang-select" title="<?php echo $t['language']; ?>">
                    <option value="en" <?php echo $lang_code === 'en' ? 'selected' : ''; ?>>EN</option>
                    <option value="es" <?php echo $lang_code === 'es' ? 'selected' : ''; ?>>ES</option>
                    <option value="fr" <?php echo $lang_code === 'fr' ? 'selected' : ''; ?>>FR</option>
                    <option value="hi" <?php echo $lang_code === 'hi' ? 'selected' : ''; ?>>HI</option>
                    <option value="zh" <?php echo $lang_code === 'zh' ? 'selected' : ''; ?>>ZH</option>
                </select>
            </div>

            <button class="icon-btn theme-btn" id="theme-toggle" title="<?php echo $t['theme']; ?>">
               <?php if ($theme === 'dark'): ?>
                <svg class="moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
               <?php else: ?>
                <svg class="sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
               <?php endif; ?>
            </button>
        </div>
    </nav>

    <main>
        <div class="editor-wrapper">
            <div class="editor-gutter"></div>
            <textarea id="editor" placeholder="<?php echo $t['placeholder']; ?>" spellcheck="false" style="font-family: <?php echo $font_family; ?>; font-size: <?php echo $font_size; ?>;"><?php echo htmlspecialchars($content); ?></textarea>
        </div>
        
        <div class="status-dock">
            <span id="save-status" class="status-item hidden" data-saving="<?php echo $t['saving']; ?>" data-saved="<?php echo $t['saved']; ?>"><?php echo $t['saved']; ?></span>
            <div class="dock-divider"></div>
            <span id="word-count" class="status-item">0 Words</span>
            <span id="char-count" class="status-item">0 Chars</span>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>
