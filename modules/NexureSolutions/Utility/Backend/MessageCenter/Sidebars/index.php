<?php

    function renderSidebar($pagetitle, $pagesubtitle) {

        echo '<aside class="caliweb-sidebar"><ul class="sidebar-list-linked">
            <div class="messageCenterTopTools">
                <div class="display-flex align-center" style="justify-content: space-between;">
                    <div>
                        <input class="form-input" name="searchChats" id="searchChats" type="text" style="padding:6px 12px; margin-top:0;" placeholder="Search your messages..."/>
                    </div>
                    <div>
                        <span class="lnr lnr-plus-circle"></span>
                    </div>
                </div>
            </div>
            <div class="messageDMs">
                <div class="messageHeaderPreview display-flex align-center" style="justify-content:space-between;">
                    <div>
                        <p class="messageSentUser">{Legal Name}</p>
                    </div>
                    <div>
                        <p class="messageSentUser">{Date Recived}</p>
                    </div>
                </div>
                <div class="messageBodyPreview">
                    <p class="messagePreviewContent">This is some funny message content that will be used for testing...</p>
                </div>
            </div>
        </ul></aside>';

    }

    renderSidebar($pagetitle, $pagesubtitle);

?>