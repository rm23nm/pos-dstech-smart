<?php
$html = file_get_contents('http://localhost:8001/billing-new');
if ($html === false) {
    echo "Error fetching local billing-new\n";
} else {
    echo "Local billing-new loaded successfully. Length: " . strlen($html) . "\n";
}
