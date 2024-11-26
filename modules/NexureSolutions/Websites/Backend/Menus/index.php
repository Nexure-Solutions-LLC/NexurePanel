<?php

    $tabs = [
        "General" => "#",
        "Site Editor" => "#",
        "Terminal" => "#",
        "File Manager" => "#",
        "Settings" => "/modules/NexureSolutions/Websites/settings",
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