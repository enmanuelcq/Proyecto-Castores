<?php

session_destroy();
if(headers_sent()){
    echo '
        <script>
            window.location.href="index.php?View=home";
        </script>
    ';
    }else{
        header("Location: index.php?View=home");
    }