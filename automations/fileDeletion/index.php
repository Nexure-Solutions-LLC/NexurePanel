<?php

$imageDir = $_SERVER["DOCUMENT_ROOT"].'/modules/graphSQL/barGraphs/';
$timeLimit = 86400;
$currentTime = time();

// Iterate over the files in the directory

foreach (new DirectoryIterator($imageDir) as $fileInfo) {

    if ($fileInfo->isFile() && $fileInfo->getExtension() == 'png') {

        // Check if the file is older than the time limit

        if ($currentTime - $fileInfo->getCTime() > $timeLimit) {

            // Delete the file

            unlink($fileInfo->getRealPath());

        }

    }

}

?>