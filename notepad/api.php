<?php
$lifetime = 60 * 60 * 24 * 30; // 30 days
session_set_cookie_params($lifetime);
session_start();

header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'save') {
    if (isset($_POST['content'])) {
        $_SESSION['notepad_content'] = $_POST['content'];
        // Also handling font preferences in session
        if (isset($_POST['font_size'])) $_SESSION['font_size'] = $_POST['font_size'];
        if (isset($_POST['font_family'])) $_SESSION['font_family'] = $_POST['font_family'];
        if (isset($_POST['theme'])) $_SESSION['theme'] = $_POST['theme'];
        
        echo json_encode(['status' => 'success', 'message' => 'Saved']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No content']);
    }
} elseif ($action === 'load') {
    $content = isset($_SESSION['notepad_content']) ? $_SESSION['notepad_content'] : '';
    $font_size = isset($_SESSION['font_size']) ? $_SESSION['font_size'] : '16px';
    $font_family = isset($_SESSION['font_family']) ? $_SESSION['font_family'] : 'Inter';
    $theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'dark'; // Default to dark as it looks better/premium
    
    echo json_encode([
        'status' => 'success',
        'content' => $content,
        'font_size' => $font_size,
        'font_family' => $font_family,
        'theme' => $theme
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>
