<?php
// Execute the 'ls' command
$output = [];
exec('cd .. && ls', $output);

// Display the output
foreach ($output as $line) {
    echo $line . "<br>";
}
?>
