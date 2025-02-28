<?php

    $pagetitle = "Contacts";
    $pagesubtitle = "List of Contacts";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/tables/contactTables/index.php');
    
    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">

                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div class="display-flex align-center" style="justify-content: space-between;">
                            <div class="display-flex align-center">
                                <div class="no-padding margin-10px-right icon-size-formatted">
                                    <img src="/assets/img/systemIcons/contactsicon.png" alt="Contacts Page Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">Contacts</p>
                                    <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">List Contacts</h4>
                                </div>
                            </div>
                            <div>
                                <a href="/dashboard/administration/contacts/createContact/" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            
                            <?php

                                contactsHomeListingTable(
                                    $con,
                                    "SELECT * FROM nexure_ownershipinformation",
                                    ['Legal Name', 'Email', 'Date of Birth', 'Address Line 1', 'Address Line 2', 'City', 'State', 'Postal Code', 'Country', 'Actions'],
                                    ['legalName', 'emailAddress', 'dateOfBirth', 'addressline1', 'addressline2', 'city', 'state', 'postalcode', 'country'],
                                    ['14%', '15%', '10%', '15%', '10%', '8%', '5%', '8%', '10%'],
                                    [
                                        'View' => "/dashboard/administration/contacts/manageContact/?id={id}",
                                        'Edit' => "/dashboard/administration/contacts/editContact/?id={id}",
                                        'Delete' => "openModal({id})"
                                    ]
                                );

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