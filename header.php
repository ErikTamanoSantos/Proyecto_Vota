<?php
    session_start();
    echo "
        <header>
            <span>".(isset($_SESSION["username"]) ? $_SESSION["username"] : "")."<span>
            <div id='nav_menu'>
                <a href='dashboard.php'>Dashboard</a>
                <a href='index.php'>Logout</a>
            </div>
        </header>
    ";
?>