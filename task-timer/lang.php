<?php
session_start();

$languages = [
    'en' => 'English',
    'es' => 'Español',
    'hi' => 'हिन्दी',
    'zh' => '中文'
];

$lang_code = 'en';
if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $languages)) {
    $lang_code = $_GET['lang'];
    $_SESSION['lang'] = $lang_code;
} elseif (isset($_SESSION['lang'])) {
    $lang_code = $_SESSION['lang'];
}

$translations = [
    'en' => [
        'title' => 'Task Timer — Premium Productivity',
        'hero_title' => 'Manage your <span class="gradient-text">Focus.</span>',
        'hero_subtitle' => 'Create tasks with custom timers to stay productive and organized.',
        'task_placeholder' => 'What needs to be done?',
        'desc_placeholder' => 'Add notes or sub-tasks (optional)...',
        'duration_placeholder' => 'Min',
        'add_task' => 'Add Task',
        'active_tasks' => 'Active Tasks',
        'empty_state' => 'No tasks yet. Start by adding one above!',
        'focus_mode' => 'Focus Mode',
        'exit_focus' => 'Exit Focus',
        'completed' => 'Completed',
        'delete' => 'Delete',
        'start' => 'Start',
        'pause' => 'Pause',
        'settings' => 'Settings',
        'lang_name' => 'English'
    ],
    'es' => [
        'title' => 'Temporizador de Tareas — Productividad Premium',
        'hero_title' => 'Gestiona tu <span class="gradient-text">Enfoque.</span>',
        'hero_subtitle' => 'Crea tareas con temporizadores personalizados para mantenerte productivo.',
        'task_placeholder' => '¿Qué hay que hacer?',
        'desc_placeholder' => 'Añadir notas o subtareas (opcional)...',
        'duration_placeholder' => 'Min',
        'add_task' => 'Añadir Tarea',
        'active_tasks' => 'Tareas Activas',
        'empty_state' => 'No hay tareas aún. ¡Empieza añadiendo una arriba!',
        'focus_mode' => 'Modo de Enfoque',
        'exit_focus' => 'Salir del Enfoque',
        'completed' => 'Completada',
        'delete' => 'Eliminar',
        'start' => 'Iniciar',
        'pause' => 'Pausa',
        'settings' => 'Ajustes',
        'lang_name' => 'Español'
    ],
    'hi' => [
        'title' => 'कार्य टाइमर — प्रीमियम उत्पादकता',
        'hero_title' => 'अपना <span class="gradient-text">फोकस</span> प्रबंधित करें।',
        'hero_subtitle' => 'उत्पादक और व्यवस्थित रहने के लिए कस्टम टाइमर के साथ कार्य बनाएं।',
        'task_placeholder' => 'क्या करने की आवश्यकता है?',
        'desc_placeholder' => 'नोट्स या उप-कार्य जोड़ें (वैकल्पिक)...',
        'duration_placeholder' => 'मिनट',
        'add_task' => 'कार्य जोड़ें',
        'active_tasks' => 'सक्रिय कार्य',
        'empty_state' => 'अभी तक कोई कार्य नहीं है। ऊपर एक जोड़कर शुरू करें!',
        'focus_mode' => 'फोकस मोड',
        'exit_focus' => 'फोकस से बाहर निकलें',
        'completed' => 'पूरा हुआ',
        'delete' => 'हटाएं',
        'start' => 'शुरू करें',
        'pause' => 'रोकें',
        'settings' => 'सेटिंग्स',
        'lang_name' => 'हिन्दी'
    ],
    'zh' => [
        'title' => '任务计时器 — 高级生产力',
        'hero_title' => '管理您的<span class="gradient-text">专注力。</span>',
        'hero_subtitle' => '使用自定义计时器创建任务，保持高效和有序。',
        'task_placeholder' => '需要做什么？',
        'desc_placeholder' => '添加备注或子任务 (可选)...',
        'duration_placeholder' => '分钟',
        'add_task' => '添加任务',
        'active_tasks' => '进行中的任务',
        'empty_state' => '暂无任务。从上方添加一个开始吧！',
        'focus_mode' => '专注模式',
        'exit_focus' => '退出专注',
        'completed' => '已完成',
        'delete' => '删除',
        'start' => '开始',
        'pause' => '暂停',
        'settings' => '设置',
        'lang_name' => '中文'
    ]
];

$t = $translations[$lang_code];
?>
