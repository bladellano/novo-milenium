<?php

namespace Source\Model;

use \Source\DB\Sql;
use \Source\Model;



class PhotosAlbums extends Model
{

    public static function lisAll()
    {
        $sql = new Sql();
        return $sql->select("SELECT *,(SELECT image FROM photos p WHERE p.id = pa.id_photos_cover) as image 
        FROM photos_albums pa ORDER BY pa.id DESC LIMIT 9;");
    }

    public function getPhotos($id)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM photos WHERE id_photos_albums = :id", ['id' => $id]);
        $this->setData($results);
    }
}
