<?php

    if ($pagetitle == "Cali Mail") {

        if ($emails) {
            rsort($emails); 

            foreach ($emails as $email_number) {
                $overview = imap_fetch_overview($inbox, $email_number, 0);
                $date = isset($overview[0]->date) ? formatDate($overview[0]->date) : 'Unknown date';

                $sender = decodeMimeStr($overview[0]->from);     

                echo '
                    
                    <div class="caliweb-email-listing" onclick="loadEmailContent('.$email_number.')">
                        <div class="caliweb-email-listing-header display-flex align-center" style="justify-content:space-between">
                            <div>
                                <p style="font-size:12px; font-weight:300;">'.$sender.'</p>
                            </div>
                            <div>
                                <p style="font-size:12px; font-weight:300;">'.$date.'</p>
                            </div>
                        </div>
                        <div class="caliweb-email-listing-body">
                            <p style="font-size:12px; font-weight:700;">

                ';

                                if ($overview[0]->subject == "") {
                                    
                                    echo '(No Subject)';

                                } else {

                                    $subject = decodeMimeStr($overview[0]->subject);

                                    echo $subject;

                                }     

                echo '

                            </p>
                        </div>
                    </div>

                ';

            }

        }

    } else {

        header("location:/error/genericSystemError");

    }

?>