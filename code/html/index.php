<?php
// phpinfo();

$extensions = get_loaded_extensions();

echo "Available PHP extensions:\n";

foreach ($extensions as $extension) {
    echo $extension . "<br>";
}