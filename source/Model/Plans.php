<?php

namespace Source\Model;

use \Source\DB\Sql;
use \Source\Model;


class Plans extends Model
{

    public static function lisAll()
    {
        $sql = new Sql();
        return $sql->select("SELECT * FROM planos ORDER BY id ASC");
    }
}
