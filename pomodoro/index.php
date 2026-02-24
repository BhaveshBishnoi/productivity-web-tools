<?php
require_once 'lang.php';

// Default settings
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'dark';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['title']; ?></title>
    <link rel="stylesheet" href="style.css">
    <meta name="description" content="<?php echo $t['meta_description']; ?>">
    
    <!-- PWA capable -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
</head>
<body>
    <nav class="nav-dock">
        <div class="logo" onclick="window.location.href='./'" style="cursor:pointer">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <span>Focus</span>
        </div>
        
        <div class="nav-center">
            <button class="mode-btn active" data-mode="work"><?php echo $t['work']; ?></button>
            <button class="mode-btn" data-mode="short"><?php echo $t['short_break']; ?></button>
            <button class="mode-btn" data-mode="long"><?php echo $t['long_break']; ?></button>
        </div>

        <div class="divider"></div>

        <div class="nav-right">
            <div class="select-wrapper">
                <select id="more-pages" onchange="if(this.value) window.location.href='./' + this.value;" style="width: auto; padding-right: 24px;">
                    <option value=""><?php echo $t['more']; ?></option>
                    <option value="about"><?php echo $t['about']; ?></option>
                    <option value="what-is-pomodoro"><?php echo $t['what_is']; ?></option>
                    <option value="privacy"><?php echo $t['privacy']; ?></option>
                    <option value="disclaimer"><?php echo $t['disclaimer']; ?></option>
                    <option value="ads-policy"><?php echo $t['ads_policy']; ?></option>
                </select>
            </div>

            <div class="divider"></div>

            <div class="select-wrapper">
                <select id="lang-select">
                    <option value="en" <?php echo $lang_code === 'en' ? 'selected' : ''; ?>>EN</option>
                    <option value="es" <?php echo $lang_code === 'es' ? 'selected' : ''; ?>>ES</option>
                    <option value="fr" <?php echo $lang_code === 'fr' ? 'selected' : ''; ?>>FR</option>
                    <option value="hi" <?php echo $lang_code === 'hi' ? 'selected' : ''; ?>>HI</option>
                    <option value="zh" <?php echo $lang_code === 'zh' ? 'selected' : ''; ?>>ZH</option>
                </select>
            </div>
            
            <button class="icon-btn" id="theme-toggle">
                <?php if ($theme === 'dark'): ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                <?php else: ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                <?php endif; ?>
            </button>
        </div>
    </nav>

    <main>
        <div class="timer-card">
            <div id="timer-display">25:00</div>
            <div class="timer-controls">
                <button id="start-btn" class="btn-primary" data-start="<?php echo $t['start']; ?>" data-pause="<?php echo $t['pause']; ?>"><?php echo $t['start']; ?></button>
                <button id="reset-btn" class="btn-secondary"><?php echo $t['reset']; ?></button>
            </div>
        </div>

        <div class="tasks-container">
            <div class="tasks-header">
                <h2><?php echo $t['tasks']; ?></h2>
            </div>
            <div class="add-task-box">
                <input type="text" id="task-input" placeholder="<?php echo $t['add_task']; ?>">
                <button class="btn-secondary" id="add-task-btn">+</button>
            </div>
            <ul class="task-list" id="task-list">
                <!-- Tasks will be injected here -->
            </ul>
        </div>
    </main>

    <div class="status-dock">
        <div class="status-item">
            <span class="status-label"><?php echo $t['sessions']; ?>:</span>
            <span class="status-value" id="sessions-count">0</span>
        </div>
        <div class="divider"></div>
        <div class="status-item">
            <span class="status-label"><?php echo $t['completed']; ?>:</span>
            <span class="status-value" id="completed-tasks-count">0</span>
        </div>
    </div>

    <!-- Audio for alert -->
    <audio id="alert-sound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

    <script src="script.js"></script>
</body>
</html>
