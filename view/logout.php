
<?php
session_start();

if(isset($_SESSION['userID'])){

    session_destroy();

    echo "<script>location.href='landing_page.php'</script>";
}
else{

    echo "<script>location.href='landing_page.php'</script>";
}
