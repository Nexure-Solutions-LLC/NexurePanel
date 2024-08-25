<?php

    require($_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Utility/Backend/System/Dashboard.php");

?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $pagetitle ?> - <?php echo $pagesubtitle ?></title>
        <meta name="author" content="Cali Web Design Development Team, Nick Derry, Michael Brinkley">
        <link href="https://caliwebdesignservices.com/assets/css/2024-01-29-styling.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="apple-touch-icon" sizes="180x180" href="https://caliwebdesignservices.com/assets/img/favico/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://caliwebdesignservices.com/assets/img/favico/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="https://caliwebdesignservices.com/assets/img/favico/favicon-16x16.png">
        <link rel="manifest" href="https://caliwebdesignservices.com/assets/img/favico/site.webmanifest">
        <link rel="stylesheet" href="/modules/CaliWebDesign/Websites/siteBuilder/assets/css/style.css" type="text/css">
    </head>
    <body class="dark-mode">
        <!-- 
    
            Make the page default dark all the time regardless of user theme.
            Designers should be dark regardless of what the panel is set to.
         
        -->