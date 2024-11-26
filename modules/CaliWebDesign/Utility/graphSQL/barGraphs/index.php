<?php

    function generateImagePath($basePath, $graphType, $theme) {

        return $basePath . '/' . session_id() . '_' . $theme . '_' . $graphType . '.png';

    }

    function createSalesPerformanceGraph($theme, $graphType, $sqlQuery) {

        global $con;

        $width = 850;
        $height = 400;
        $image = imagecreate($width, $height);

        if ($theme == 'dark-mode') {

            $backgroundColor = imagecolorallocate($image, 20, 20, 20);
            $textColor = imagecolorallocate($image, 255, 255, 255);
            $barColor = imagecolorallocate($image, 199, 149, 245);

        } else {

            $backgroundColor = imagecolorallocate($image, 255, 255, 255);
            $textColor = imagecolorallocate($image, 0, 0, 0);
            $barColor = imagecolorallocate($image, 199, 149, 245);

        }

        imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);

        $result = $con->query($sqlQuery);

        $data = [];

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                $data[$row['salesPerson']] = $row['salesPerformance'];

            }

            $margin = 40;
            $barWidth = 60;
            $barSpacing = 80;
            $maxValue = max($data);
            $scale = ($height - 2 * $margin) / $maxValue;

            $fontPath = $_SERVER["DOCUMENT_ROOT"].'/assets/fonts/IBMPlexSans-Regular.ttf';

            if (!file_exists($fontPath)) {

                die('Font file not found.');

            }

            $x = $margin;

            foreach ($data as $category => $value) {

                $barHeight = round($value * $scale);
                imagefilledrectangle($image, (int)$x, $height - $margin, (int)($x + $barWidth), (int)($height - $margin - $barHeight), $barColor);
                imagettftext($image, 10, 0, (int)($x + ($barWidth / 2) - (strlen($category) * 2.5)), $height - $margin + 20, $textColor, $fontPath, $category);
                $x += $barWidth + $barSpacing;

            }

            $imagePath = generateImagePath($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/graphSQL/barGraphs', $graphType, $theme);
            imagepng($image, $imagePath);
            imagedestroy($image);

            echo '<img src="/modules/CaliWebDesign/Utility/graphSQL/barGraphs/' . basename($imagePath) . '?t=' . time() . '" alt="Bar Graph">';

        } else {

            echo '

                <div class="" style="margin-left:auto; margin-right:auto; text-align:center; padding-bottom:4%;">
                    <img src="/assets/img/graphicsVectorDrawings/salesPersonActivityNoContent.svg" style="width:40%; margin-top:2%; height:20vh;" alt="Bar Graph Not Found Graphic Vector" >
                    <p style="margin-top:4%; font-size:14px;">When you add Sales People their performance will show.</p>
                </div>

            ';

        }

    }

    if ($_SESSION['graphCallType'] == "Sales Person Activity") {

        $theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light-mode';

        $sql = "SELECT salesPerson, salesPerformance FROM nexure_salesperformance";

        createSalesPerformanceGraph($theme, 'salesPerformance', $sql);

    } else if ($_SESSION['graphCallType'] == "Employee Only Sales Activity") {

        $theme = isset($_GET['theme']) ? $_GET['theme'] : 'light-mode';

        $fullname = $currentAccount->legalName;

        $sql = "SELECT salesPerson, salesPerformance FROM nexure_salesperformance WHERE salesPerson = '$fullname'";

        createSalesPerformanceGraph($theme, 'employeeSalesPerformance', $sql);
    }

?>
