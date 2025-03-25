<?php

    $tabs = [
        "Blacklist Details" => "/modules/NexureSolutions/Blacklister/ViewBlacklist/?account_number=$accountnumber",
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