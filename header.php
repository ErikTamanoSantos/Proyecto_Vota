<?php
    session_start();
    echo "
        <header>
            <span class='spanNavBar'>".(isset($_SESSION["username"]) ? $_SESSION["username"] : "")."<span>
            <div id='nav_menu' class='flex flex-row space-between items-center navbar'>
                <div class='logo'> 
                    <a href='index.php'>Vota EJA</a>
                </div>
                <div class='items'>
                    <a href='index.php'>Inicio</a>
                    <a href='votaciones.php'>Votaciones</a>
                </div>
                <div class='userInterface'>
                ";
                
                if(isset($_SESSION["username"])){
                    echo "
                        <a href='dashboard.php'>Dashboard</a>
                        <a href='index.php'>Salir</a>";
                } else{
                    echo "
                        <a href='login.php'>Inicia sesión</a>
                        <a href='register.php'>Regístrate</a>";
                }
            
                echo "    
                </div>
            </div>
        </header>
    ";
?>