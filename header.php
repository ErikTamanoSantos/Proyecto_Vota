<?php
    session_start();
    echo "
        <header>
            <span>".(isset($_SESSION["username"]) ? $_SESSION["username"] : "")."<span>
            <div id='nav_menu'>";
                if(isset($_SESSION["username"])){
                    echo "
                        <a href='dashboard.php'>Dashboard</a>
                        <a href='index.php'>Logout</a>";
                } else{
                    echo "
                        <a href='login.php'>Login</a>
                        <a href='register.php'>Register</a>";
                }
            echo "    
            </div>
        </header>
    ";
?>