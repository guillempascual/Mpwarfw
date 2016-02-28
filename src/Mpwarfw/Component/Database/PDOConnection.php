<?php

namespace Mpwarfw\Component\Database;

use Exception;
use PDO;

class PDOConnection implements DatabaseConnection
{
    protected $pdo = null;

    public function __construct($host,$dbname,$user='root',$password='vagrantpass')
    {
        try{
            $this->pdo = new \PDO( 'mysql:host='.$host.';dbname='.$dbname, $user, $password );
        }
        catch(Exception $e) {
            die('Error: '.$e->getMessage());
        }
    }

    public function execute($query, $params)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
