<?php

    echo '
    
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Cali Web Design Panel Account Denied</title>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
                <style>
                    body {
                        font-family: "IBM Plex Sans", sans-serif;
                        background-color: #f6f9fc;
                        margin: 0;
                        padding: 0;
                    }
                    .careers-link,
                    .dark-mode a,
                    .purchase-catalog-link {
                        color: #676cff;
                    }
                    .careers-link:hover,
                    .dark-mode a:hover,
                    .purchase-catalog-link:hover {
                        color: #868aff;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 0 auto;
                        margin-top:2%;
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
                    .email-header {
                        text-align: left;
                        padding-bottom: 20px;
                    }
                    .email-header img {
                        max-width: 100px;
                    }
                    .email-body {
                        padding: 20px;
                        background-color: #fff;
                        border-radius: 8px;
                        font-size:14px;
                    }
                    .email-footer {
                        text-align: left;
                        font-size: 12px;
                        color: #6b7c93;
                        padding-top: 20px;
                    }
                    .email-footer a {
                        color: #6b7c93;
                        text-decoration: none;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <div class="email-header">
                        <img src="'.$variableDefinitionX->orglogolight.'" alt="'.$variableDefinitionX->orgShortName.' Logo">
                    </div>
                    <div class="email-body">
                        <p>Hi,</p>
                        <p>We recently recived a request for a '.$variableDefinitionX->orgShortName.' account. Unfortuantly we could not open this account. More information about this denial will be mailed to the address that you supplied to us. You can reapply in 30 days if you belive this decision was made in error.</p>
                        <p>— The '.$variableDefinitionX->orgShortName.' Team</p>
                    </div>
                    <div class="email-footer">
                        <p>This email relates to your '.$variableDefinitionX->orgShortName.' account.<br>
                        Need to refer to this message? Use this ID: <strong>'.$emailID.'</strong></p>
                        <p>'.$variableDefinitionX->orgShortName.', P.O. Box 415 Nottingham, PA 19363 US</p>
                    </div>
                </div>
            </body>
        </html>
    
    ';

?>