<?php
    session_start();
    session_destroy();

    setcookie("token", "", time()-1, "/");
    setcookie("idUsuario", "", time()-1, "/");

    header("Location: login.html");
?>