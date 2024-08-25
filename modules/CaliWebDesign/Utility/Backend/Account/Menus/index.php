<?php

    $tabs = [
        "Details" => "/dashboard/administration/accounts/manageAccount/?account_number=$accountnumber",
        "Payment Methods" => "/dashboard/administration/accounts/manageAccount/paymentMethods/?account_number=$accountnumber",
        "Relationships" => "/dashboard/administration/accounts/manageAccount/relationships/?account_number=$accountnumber",
        "Goals" => "/dashboard/administration/accounts/manageAccount/goals/?account_number=$accountnumber",
        "Related" => "/dashboard/administration/accounts/manageAccount/related/?account_number=$accountnumber",
        "Manage Profile" => "/dashboard/administration/accounts/manageAccount/profile/?account_number=$accountnumber"
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