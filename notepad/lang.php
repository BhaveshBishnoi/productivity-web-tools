<?php
$lifetime = 60 * 60 * 24 * 30; // 30 days
session_set_cookie_params($lifetime);
session_start();

// Default language
$lang_code = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en');
$_SESSION['lang'] = $lang_code;

// Translations
$translations = [
    'en' => [
        'title' => 'Global Notepad',
        'meta_description' => 'A premium online notepad with multi-language support, dark mode, and persistent saves. Write and save your notes securely in the cloud.',
        'meta_keywords' => 'online notepad, web text editor, secure notes, dark mode notepad, multi-language editor',
        'file' => 'File',
        'new' => 'New',
        'open' => 'Open',
        'save' => 'Save',
        'settings' => 'Settings',
        'font_size' => 'Font Size',
        'font_family' => 'Font Family',
        'theme' => 'Theme',
        'light' => 'Light',
        'dark' => 'Dark',
        'placeholder' => 'Start typing your notes here...',
        'saved' => 'Saved!',
        'saving' => 'Saving...',
        'language' => 'Language'
    ],
    'es' => [
        'title' => 'Bloc de Notas Global',
        'meta_description' => 'Un bloc de notas y editor de texto online premium con soporte multi-idioma, modo oscuro y guardado automático.',
        'meta_keywords' => 'bloc de notas online, editor de texto, notas seguras, modo oscuro, editor multilingüe',
        'file' => 'Archivo',
        'new' => 'Nuevo',
        'open' => 'Abrir',
        'save' => 'Guardar',
        'settings' => 'Configuración',
        'font_size' => 'Tamaño de Fuente',
        'font_family' => 'Familia de Fuentes',
        'theme' => 'Tema',
        'light' => 'Claro',
        'dark' => 'Oscuro',
        'placeholder' => 'Empieza a escribir tus notas aquí...',
        'saved' => '¡Guardado!',
        'saving' => 'Guardando...',
        'language' => 'Idioma'
    ],
    'fr' => [
        'title' => 'Bloc-notes Mondial',
        'meta_description' => 'Un bloc-notes en ligne premium avec support multilingue, mode sombre et sauvegardes persistantes.',
        'meta_keywords' => 'bloc-notes en ligne, éditeur de texte, notes sécurisées, mode sombre, éditeur multilingue',
        'file' => 'Fichier',
        'new' => 'Nouveau',
        'open' => 'Ouvrir',
        'save' => 'Enregistrer',
        'settings' => 'Paramètres',
        'font_size' => 'Taille de police',
        'font_family' => 'Police',
        'theme' => 'Thème',
        'light' => 'Clair',
        'dark' => 'Sombre',
        'placeholder' => 'Commencez à taper vos notes ici...',
        'saved' => 'Enregistré!',
        'saving' => 'Enregistrement...',
        'language' => 'Langue'
    ],
    'hi' => [
        'title' => 'ग्लोबल नोटपैड',
        'meta_description' => 'मल्टी-लैंग्वेज सपोर्ट, डार्क मोड और ऑटो-सेव के साथ एक प्रीमियम ऑनलाइन नोटपैड। अपनी नोट्स सुरक्षित रूप से लिखें।',
        'meta_keywords' => 'ऑनलाइन नोटपैड, टेक्स्ट एडिटर, सुरक्षित नोट्स, डार्क मोड, हिंदी टाइपिंग',
        'file' => 'फ़ाइल',
        'new' => 'नया',
        'open' => 'खोलें',
        'save' => 'सहेजें',
        'settings' => 'सेटिंग्स',
        'font_size' => 'फ़ॉन्ट आकार',
        'font_family' => 'फ़ॉन्ट परिवार',
        'theme' => 'थीम',
        'light' => 'लाइट',
        'dark' => 'डार्क',
        'placeholder' => 'अपनी नोट्स यहाँ लिखना शुरू करें...',
        'saved' => 'सहेजा गया!',
        'saving' => 'सहेजा जा रहा है...',
        'language' => 'भाषा'
    ],
    'zh' => [
        'title' => '全球记事本',
        'meta_description' => '具有多语言支持、暗模式和持久保存功能的高级在线记事本。',
        'meta_keywords' => '在线记事本, 文本编辑器,以此笔记, 暗模式, 多语言',
        'file' => '文件',
        'new' => '新建',
        'open' => '打开',
        'save' => '保存',
        'settings' => '设置',
        'font_size' => '字体大小',
        'font_family' => '字体系列',
        'theme' => '主题',
        'light' => '亮色',
        'dark' => '暗色',
        'placeholder' => '在这里开始输入您的笔记...',
        'saved' => '已保存!',
        'saving' => '保存中...',
        'language' => '语言'
    ]
];

$t = isset($translations[$lang_code]) ? $translations[$lang_code] : $translations['en'];
?>
