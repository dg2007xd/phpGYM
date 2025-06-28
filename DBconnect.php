<?php

header("Access-Control-Allow-Origin: *");
class DBconnect{
    public function connect()
    {
        //$cn = new PDO("mysql:host=localhost;dbname=gym", "root", "");
        $cn = new PDO("mysql:host=mysql-dg2007xd.alwaysdata.net;dbname=dg2007xd_wbgym", "dg2007xd", "wbgym.2025");
        $cn->query("SET CHARACTER SET utf8mb4");
        return $cn;
    }
}
