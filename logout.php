<?php
session_start();
session_destroy();
?>
<script type="text/javascript">
    alert('selamat anda berhasil logout.');
    location.href = "login.php";
</script>