<?php
    define("ROOT_PATH", dirname(__FILE__));
    define("SITE_URL", "http://test.loc");
    define("API_URL", "http://api.test.loc/");
    ini_set("session.gc_maxlifetime", 30);
    session_start();