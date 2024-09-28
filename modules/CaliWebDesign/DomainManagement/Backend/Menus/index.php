<?php

    $tabs = [
        "General" => "#",
        "DNS" => "#",
        "Emails" => "#",
        "Transfer" => "#",
        "Nameservers" => "#",
        "Settings" => "/modules/CaliWebDesign/Discord/botManagement/settings",
    ];

    echo '
    
        <div class="tab-switcher">
            <ul class="display-flex align-center tab-switch-ul">
            
    ';

                foreach ($tabs as $title => $url) {

                    $activeClass = ($title == $pagesubtitle) ? 'active' : '';

                    echo "<li class='tab-switch-tab $activeClass'><a href='$url'>$title</a></li>";

                }
    
    echo '  
            
            </ul>
        </div>
        
    ';

?>