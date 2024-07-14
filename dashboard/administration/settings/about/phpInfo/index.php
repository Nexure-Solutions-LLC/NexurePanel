<?php
// Start output buffering to capture phpinfo() content
ob_start();
phpinfo();
$info = ob_get_clean();

// Remove body tags to extract only the table content
$info = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $info);

// Apply custom styling to specific elements
$info = preg_replace_callback(
    '/(<h1.*?>)(.*?)<\/h1>/s',
    function($matches) {
        return '<h1 style="font-size: 20px; margin-top: 20px;">' . $matches[2] . '</h1>';
    },
    $info
);

$info = preg_replace(
    '/<table([^>]*)>/',
    '<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">',
    $info
);

$info = preg_replace_callback(
    '/(<img.*?\/>)/',
    function($matches) {
        return '<div class="php-logo">' . $matches[1] . '</div>';
    },
    $info
);

// Output integrated into your panel's HTML structure
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Info in Panel</title>
    <style>
        .panel-container {
            max-width: 100%;
            background-color: #fff;
            border-radius: 8px;
        }
        .php-logo {
            max-width: 100px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 0;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="panel-container">
        <?php echo $info; ?>
    </div>
</body>
</html>