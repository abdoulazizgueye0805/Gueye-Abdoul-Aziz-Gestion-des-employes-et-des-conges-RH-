<?php

class Database
{
    private string $host = "localhost";
    private string $dbname = "gestion_rh";
    private string $user = "root";
    private string $password = "";
    private ?PDO $connexion = null;

    public function getConnexion(): PDO
    {
        if ($this->connexion === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";
            $this->connexion = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return $this->connexion;
    }
}
