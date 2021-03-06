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
    public static function getCategory($id)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT category FROM articles_categories WHERE id = :id", ['id' => $id]);
        return $results[0]['category'];
    }
}
