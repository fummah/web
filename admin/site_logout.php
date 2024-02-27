<?php
error_reporting(0);
session_start();
session_unset();
session_destroy();
session_write_close();

?>
<script type="text/javascript">
    location.href = "https://medclaimassist.co.za/login/";
</script>
