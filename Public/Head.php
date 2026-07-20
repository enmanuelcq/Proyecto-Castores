<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Deportivo Los Castores - Academia de Fútbol</title>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="./Public/css/
<?php 
    if (isset($_GET['View'])) {
        $view = $_GET['View'];
        echo $view;
    } else {
        echo 'home';
    }
?>.css">
<link rel="stylesheet" href="./Public/css/Styles.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>