<?php

namespace Mpwarfw\Component\Database;

interface DatabaseConnection
{
    public function __construct($host,$dbname,$user,$password);
    public function execute($query,$arrayOfParams);
}
