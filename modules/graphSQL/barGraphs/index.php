<?php
    if ($_SESSION['graphCallType'] == "Sales Person Activity") {
        $theme = isset($_GET['theme']) ? $_GET['theme'] : 'light-mode';

        $width = 850;
        $height = 400;
        $image = imagecreate($width, $height);

        if ($theme == 'dark-mode') {
            $backgroundColor = imagecolorallocate($image, 29, 29, 29);
            $textColor = imagecolorallocate($image, 255, 255, 255);
            $barColor = imagecolorallocate($image, 199, 149, 245);
        } else {
            $backgroundColor = imagecolorallocate($image, 255, 255, 255);
            $textColor = imagecolorallocate($image, 0, 0, 0);
            $barColor = imagecolorallocate($image, 199, 149, 245);
        }

        imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);

        $sql = "SELECT salesPerson, salesPerformance FROM caliweb_salesperformance";
        $result = $con->query($sql);

        $data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
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
                $barHeight = round($value * $scale); // Round the value
                imagefilledrectangle($image, (int)$x, $height - $margin, (int)($x + $barWidth), (int)($height - $margin - $barHeight), $barColor);
                imagettftext($image, 10, 0, (int)($x + ($barWidth / 2) - (strlen($category) * 2.5)), $height - $margin + 20, $textColor, $fontPath, $category); // Centered text
                $x += $barWidth + $barSpacing;
            }

            $imagePath = $_SERVER["DOCUMENT_ROOT"].'/modules/graphSQL/barGraphs/salesPerformance.png';
            imagepng($image, $imagePath);
            imagedestroy($image);

            echo '<img src="/modules/graphSQL/barGraphs/salesPerformance.png?theme=' . $theme . '" alt="Bar Graph">';
        } else {
            echo "0 results";
        }
    } else if ($_SESSION['graphCallType'] == "Employee Only Sales Activity") {
        $theme = isset($_GET['theme']) ? $_GET['theme'] : 'light-mode';

        $width = 850;
        $height = 400;
        $image = imagecreate($width, $height);

        if ($theme == 'dark-mode') {
            $backgroundColor = imagecolorallocate($image, 29, 29, 29);
            $textColor = imagecolorallocate($image, 255, 255, 255);
            $barColor = imagecolorallocate($image, 199, 149, 245);
        } else {
            $backgroundColor = imagecolorallocate($image, 255, 255, 255);
            $textColor = imagecolorallocate($image, 0, 0, 0);
            $barColor = imagecolorallocate($image, 199, 149, 245);
        }

        imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);

        $sql = "SELECT salesPerson, salesPerformance FROM caliweb_salesperformance WHERE salesPerson = '$fullname'";
        $result = $con->query($sql);

        $data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
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
                $barHeight = round($value * $scale); // Round the value
                imagefilledrectangle($image, (int)$x, $height - $margin, (int)($x + $barWidth), (int)($height - $margin - $barHeight), $barColor);
                imagettftext($image, 10, 0, (int)($x + ($barWidth / 2) - (strlen($category) * 2.5)), $height - $margin + 20, $textColor, $fontPath, $category); // Centered text
                $x += $barWidth + $barSpacing;
            }

            $imagePath = $_SERVER["DOCUMENT_ROOT"].'/modules/graphSQL/barGraphs/salesPerformance.png';
            imagepng($image, $imagePath);
            imagedestroy($image);

            echo '<img src="/modules/graphSQL/barGraphs/salesPerformance.png?theme=' . $theme . '" alt="Bar Graph">';
        } else {
            echo "0 results";
        }
    }
?>