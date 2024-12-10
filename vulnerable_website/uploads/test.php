<?php
// Start the JPEG header
$jpeg_header = "\xFF\xD8\xFF\xE0\x00\x10\x4A\x46\x49\x46\x00\x01\x01\x01\x00\x60\x00\x60\x00\x00";

// Your PHP script here
$php_code = '<?php echo "Hello, world!"; ?>';

// End the JPEG footer
$jpeg_footer = "\xFF\xD9";

// Combine the parts and output
echo $jpeg_header . $php_code . $jpeg_footer;
?>
