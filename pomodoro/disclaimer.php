<?php
require_once 'lang.php';
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'dark';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['disclaimer']; ?> - Focus Flow</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav-dock">
        <div class="logo" onclick="window.location.href='./'" style="cursor:pointer">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <span>Focus</span>
        </div>
        <div class="nav-right">
            <button class="mode-btn" onclick="window.location.href='./'"><?php echo $t['home']; ?></button>
            <div class="divider"></div>
            <div class="select-wrapper">
                <select id="lang-select" onchange="window.location.search = '?lang='+this.value;">
                    <option value="en" <?php echo $lang_code === 'en' ? 'selected' : ''; ?>>EN</option>
                    <option value="es" <?php echo $lang_code === 'es' ? 'selected' : ''; ?>>ES</option>
                    <option value="fr" <?php echo $lang_code === 'fr' ? 'selected' : ''; ?>>FR</option>
                    <option value="hi" <?php echo $lang_code === 'hi' ? 'selected' : ''; ?>>HI</option>
                    <option value="zh" <?php echo $lang_code === 'zh' ? 'selected' : ''; ?>>ZH</option>
                </select>
            </div>
        </div>
    </nav>

    <main>
        <div class="info-card">
            <h1><?php echo $t['disclaimer']; ?></h1>
            <p>
                <?php echo $t['disclaimer_text']; ?>
            </p>
        </div>
    </main>
</body>
</html>
