<?php
$dir = __DIR__;
$files = scandir($dir);
echo "Files in $dir:\n";
print_r($files);
