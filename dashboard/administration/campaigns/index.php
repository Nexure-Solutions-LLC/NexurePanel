<?php

    $pagetitle = "Campaigns";
    $pagesubtitle = "List of Campaigns";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    
    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    $campaignsSystem = new \NexureSolutions\MarketingCloud\CampaignsSystem($con);
    $adPartners = $campaignsSystem->loadAdPartners();

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">

                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div class="display-flex align-center" style="justify-content: space-between;">
                            <div class="display-flex align-center">
                                <div class="no-padding margin-10px-right icon-size-formatted">
                                    <img src="/assets/img/systemIcons/campaignsicon.png" alt="Campaigns Page Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">Campaigns</p>
                                    <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">List Campaigns</h4>
                                </div>
                            </div>
                            <div>
                                <a href="/dashboard/administration/campaigns/createCampaign/" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            <?php

                                if (!empty($adPartners)) {

                                    echo "Loaded ad partners: " . implode(", ", $adPartners);

                                } else {

                                    echo '<script type="text/javascript">window.location = "/dashboard/administration"</script>';

                                }

                            ?>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>