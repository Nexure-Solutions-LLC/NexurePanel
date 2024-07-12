<?php

    if ($_SESSION['graphCallType'] == "Deals by Segment") {
        
        $theme = isset($_GET['theme']) ? $_GET['theme'] : 'light-mode';

        $width = 850;
        $height = 400;
        $image = imagecreate($width, $height);

        if ($theme == 'dark-mode') {

            $backgroundColor = imagecolorallocate($image, 29, 29, 29);
            $textColor = imagecolorallocate($image, 255, 255, 255);
            $colors = [
                imagecolorallocate($image, 255, 206, 86), // Yellow
                imagecolorallocate($image, 54, 162, 235), // Blue
                imagecolorallocate($image, 255, 99, 132), // Red
                imagecolorallocate($image, 75, 192, 192), // Teal
                imagecolorallocate($image, 153, 102, 255), // Purple
                imagecolorallocate($image, 255, 159, 64)  // Orange
            ];

        } else {

            $backgroundColor = imagecolorallocate($image, 255, 255, 255);
            $textColor = imagecolorallocate($image, 0, 0, 0);
            $colors = [
                imagecolorallocate($image, 255, 206, 86), // Yellow
                imagecolorallocate($image, 54, 162, 235), // Blue
                imagecolorallocate($image, 255, 99, 132), // Red
                imagecolorallocate($image, 75, 192, 192), // Teal
                imagecolorallocate($image, 153, 102, 255), // Purple
                imagecolorallocate($image, 255, 159, 64)  // Orange
            ];

        }

        imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);


        $sql = "SELECT segment, value FROM caliweb_leadssource";
        $result = $con->query($sql);

        $data = [];
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                $data[$row['segment']] = $row['value'];

            }

            $total = array_sum($data);
            $angleStart = 0;

            $fontPath = $_SERVER["DOCUMENT_ROOT"].'/assets/fonts/IBMPlexSans-Regular.ttf';

            if (!file_exists($fontPath)) {

                die('Font file not found.');

            }

            foreach ($data as $key => $value) {

                $angle = ($value / $total) * 360;
                $angleEnd = $angleStart + $angle;

                $colorIndex = array_search($key, array_keys($data));
                $color = $colors[$colorIndex];

                imagefilledarc(
                    $image,
                    (int)($height / 2),
                    (int)($height / 2),
                    (int)($height - 20),
                    (int)($height - 20),
                    (int)$angleStart,
                    (int)$angleEnd,
                    $color,
                    IMG_ARC_PIE
                );

                $angleStart = $angleEnd;

            }

            $white = imagecolorallocate($image, 255, 255, 255);
            $centerX = (int)($height / 2);
            $centerY = (int)($height / 2);
            $innerRadius = (int)(($height - 20) / 2 * 0.5);

            imagefilledellipse(
                $image,
                $centerX,
                $centerY,
                $innerRadius * 2,
                $innerRadius * 2,
                $white
            );

            $centerText = $total . 'M';
            $textBox = imagettfbbox(14, 0, $fontPath, $centerText);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[7] - $textBox[1];
            imagettftext(
                $image,
                14,
                0,
                (int)($centerX - ($textWidth / 2)),
                (int)($centerY - ($textHeight / 2)),
                $textColor,
                $fontPath,
                $centerText
            );

            $legendX = $height + 350;
            $legendY = 20;
            foreach ($data as $key => $value) {

                $colorIndex = array_search($key, array_keys($data));
                $color = $colors[$colorIndex];
                imagefilledrectangle($image, $legendX, $legendY, $legendX + 20, $legendY + 20, $color);
                imagettftext($image, 10, 0, $legendX + 30, $legendY + 15, $textColor, $fontPath, $key);
                $legendY += 30;

            }

            $imagePath = $_SERVER["DOCUMENT_ROOT"].'/modules/graphSQL/pieCharts/leadsSource.png';

            imagepng($image, $imagePath);
            imagedestroy($image);

            echo '<img src="/modules/graphSQL/pieCharts/leadsSource.png?theme=' . $theme . '" alt="Doughnut Chart">';
            
        } else {

            echo "0 results";

        }

    }

?>