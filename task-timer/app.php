<?php require_once 'lang.php'; 
$base = isset($base_path) ? $base_path : '';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['title']; ?></title>
    <link rel="stylesheet" href="<?php echo $base; ?>style.css">
    <!-- hello -->
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- SEO & Icons -->
    <meta name="description" content="<?php echo $t['hero_subtitle']; ?>">
    <meta name="author" content="Task Timer App">
    
    <!-- PWA capable -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
</head>
<body>
    <div class="background-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <nav class="nav-dock">
        <div class="nav-left">
            <div class="logo">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span class="logo-text">Task Timer</span>
            </div>
        </div>

        <div class="nav-center">
            <div class="divider"></div>
            <div class="select-wrapper lang-wrapper">
                <select id="lang-select" onchange="location.href='<?php echo $base; ?>' + this.value + '/index.php'">
                    <?php foreach ($languages as $code => $name): ?>
                        <option value="<?php echo $code; ?>" <?php echo $lang_code === $code ? 'selected' : ''; ?>><?php echo strtoupper($code); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="divider"></div>
            <button class="icon-btn theme-btn" id="theme-toggle" title="Toggle Theme">
                <svg class="sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
            </button>
        </div>

        <div class="nav-right">
            <span id="current-time">00:00</span>
        </div>
    </nav>

    <main>
        <div class="container" id="main-content">
            <header class="hero-section">
                <h1><?php echo $t['hero_title']; ?></h1>
                <p><?php echo $t['hero_subtitle']; ?></p>
            </header>

            <section class="task-input-area glass">
                <div class="input-stack">
                    <div class="input-row">
                        <input type="text" id="task-name" placeholder="<?php echo $t['task_placeholder']; ?>" autocomplete="off">
                        <div class="timer-input-wrapper">
                            <input type="number" id="task-duration" placeholder="<?php echo $t['duration_placeholder']; ?>" min="1" max="1440">
                            <span class="input-suffix">m</span>
                        </div>
                    </div>
                    <textarea id="task-desc" placeholder="<?php echo $t['desc_placeholder']; ?>" rows="2"></textarea>
                    <button id="add-task-btn" class="primary-btn">
                        <span><?php echo $t['add_task']; ?></span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    </button>
                </div>
            </section>

            <section class="tasks-container">
                <div class="tasks-header">
                    <h2><?php echo $t['active_tasks']; ?></h2>
                    <span id="task-count" class="badge">0</span>
                </div>
                <div id="tasks-list" class="tasks-list">
                    <div class="empty-state">
                        <p><?php echo $t['empty_state']; ?></p>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Focus Mode Overlay -->
    <div id="focus-overlay" class="focus-overlay hidden">
        <button id="exit-focus-btn" class="exit-focus-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            <span><?php echo $t['exit_focus']; ?></span>
        </button>
        <div class="focus-content">
            <h2 id="focus-task-name">Current Task</h2>
            <div class="focus-timer" id="focus-timer-display">00:00</div>
            <p id="focus-task-desc">Task description goes here.</p>
            <div class="focus-controls">
                <button id="focus-toggle-btn" class="focus-action-btn">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                </button>
            </div>
        </div>
    </div>

    <div id="notification-toast" class="toast hidden">
        <div class="toast-content">
            <div class="toast-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            </div>
            <div class="toast-text">
                <h4 id="toast-title">Task Completed!</h4>
                <p id="toast-msg">Time's up for your task.</p>
            </div>
            <button id="close-toast" class="icon-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
    </div>

    <!-- Audio for notification -->
    <audio id="timer-beep" preload="auto">
        <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
    </audio>

    <script src="<?php echo $base; ?>script.js"></script>
</body>
</html>
