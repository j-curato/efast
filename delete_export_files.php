<?php

$subdirectory = '//transaction/';
$parentDir = dirname(__FILE__);
$targetFolder = $parentDir . $subdirectory;
// Get a list of files in the folder
$files = scandir($targetFolder);
// Loop through the files and delete them

foreach ($files as $file) {

    if (is_file($targetFolder . '//' . $file)) {
        unlink($targetFolder . '//' . $file);
    }
}
