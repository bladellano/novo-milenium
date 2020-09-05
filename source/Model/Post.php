<?php

namespace Source\Model;

use \Source\DB\Sql;
use \Source\Model;

class Post extends Model
{

    public function lisAll()
    {
        $sql = new Sql();
        return $sql->select("SELECT * FROM articles ORDER BY id DESC LIMIT 6");
    }
}
