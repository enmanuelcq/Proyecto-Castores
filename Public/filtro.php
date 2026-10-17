<?php 

 if((!isset($_SESSION['usuario'])) && ($_GET['View'] != "404" && $_GET['View'] != "Noticias" && $_GET['View'] != "home" && $_GET['View'] != "Login" && $_GET['View'] != "RegistroRepresentante")){

        $_GET['View'] = "home";
    
    }

   if(isset($_SESSION['usuario'])){
    
    // Mostrar navbar según sesión y tipo de usuario
        if(($_SESSION['usuario']['id_rol'] == '1')){
            include "navbar_Admi.php";
            
        } elseif(($_SESSION['usuario']['id_rol'] == '2')){
          include "navbar_Secretaria.php";


        } elseif(($_SESSION['usuario']['id_rol'] == '3')){
          include "navbar_Entrenador.php";

        } elseif(($_SESSION['usuario']['id_rol'] == '4')){
          include "navbar_representante.php";

        }
   }else {
        
        include "navbar.php"; // Navbar básica
   } 