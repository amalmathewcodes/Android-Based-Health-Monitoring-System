<?php
include 'config.php';
session_start();
session_destroy();



?>
<script>window.location="<?php echo hostname ?>index.php"</script>