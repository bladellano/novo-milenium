<?php

namespace Source\Model;

use \Source\DB\Sql;
use \Source\Model;



class Blog extends Model
{

    public static function lisAll()
    {
        $sql = new Sql();
        return $sql->select("SELECT * FROM articles ORDER BY id DESC LIMIT 9");
    }

    public function getWithSlug($slug)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM articles WHERE slug = :slug", ['slug' => $slug]);
        $this->setData($results[0]);
    }
}
