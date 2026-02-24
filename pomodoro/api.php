<?php
$lifetime = 60 * 60 * 24 * 30; // 30 days
session_set_cookie_params($lifetime);
session_start();

header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'save_settings') {
    if (isset($_POST['work_duration'])) $_SESSION['work_duration'] = $_POST['work_duration'];
    if (isset($_POST['short_break'])) $_SESSION['short_break'] = $_POST['short_break'];
    if (isset($_POST['long_break'])) $_SESSION['long_break'] = $_POST['long_break'];
    if (isset($_POST['theme'])) $_SESSION['theme'] = $_POST['theme'];
    
    echo json_encode(['status' => 'success']);
} else if ($action === 'load') {
    echo json_encode([
        'status' => 'success',
        'work_duration' => isset($_SESSION['work_duration']) ? $_SESSION['work_duration'] : '25',
        'short_break' => isset($_SESSION['short_break']) ? $_SESSION['short_break'] : '5',
        'long_break' => isset($_SESSION['long_break']) ? $_SESSION['long_break'] : '15',
        'theme' => isset($_SESSION['theme']) ? $_SESSION['theme'] : 'dark',
        'lang' => isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en'
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>
