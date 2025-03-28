<?php
    $pagetitle = "Settings";
    $pagesubtitle = "System Setup";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/tables/settingsTables/index.php');

    use Sentry\ClientBuilder;
    use Sentry\State\Scope;

    function fetchSentryLogs($sentryOrg, $sentryProject, $sentryToken) {

        $url = "https://sentry.io/api/0/projects/$sentryOrg/$sentryProject/issues/";
    
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $sentryToken",
            "Content-Type: application/json"
        ]);
    
        $response = curl_exec($ch);

        curl_close($ch);
    
        return json_decode($response, true);

    }
    
    $logs = fetchSentryLogs($sentryOrg, $sentryProject, $sentryToken);

    echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';
?>

<section class="section first-dashboard-area-cards">
    <div class="container width-98">
        <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
            <div class="caliweb-settings-sidebar">
                <div class="caliweb-card dashboard-card sidebar-card">
                    <?php  include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Sidebars/Settings/index.php'); ?>
                </div>
            </div>
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="settings-header">
                    <div class="display-flex align-center mobile-header-compact">
                        <div class="no-padding margin-10px-right icon-size-formatted">
                            <img src="/assets/img/systemIcons/settingsicon.png" alt="Settings Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                        </div>
                        <div>
                            <p class="no-padding font-14px" style="padding-bottom:4px;">Settings</p>
                            <h4 class="text-bold font-size-16 no-padding display-flex align-center">
                                System Logs
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="caliweb-card dashboard-card" style="overflow-y:scroll; height:75vh;">
                    <div id="logs-container">
                        <h3 style="font-size:18px; margin-top:10px; margin-bottom:20px;">Sentry Logs</h3>
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                                <div class="log-entry">
                                    <p class="font-14px"><strong><?= htmlspecialchars($log['title']) ?></strong></p>
                                    <p class="font-12px"><?= htmlspecialchars($log['culprit']) ?></p>
                                    <p class="font-12px"><?= date('l F j Y h:i A', strtotime($log['lastSeen'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No logs found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>