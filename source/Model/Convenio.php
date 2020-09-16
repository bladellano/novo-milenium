<?php

namespace Source\Model;

use \Source\DB\Sql;
use \Source\Model;

class Convenio extends Model
{

    public static function lisAll()
    {
        $sql = new Sql();
        return $sql->select("SELECT * FROM convenios ORDER BY id DESC");
    }

    public function get($id)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM convenios WHERE id = :id", ['id' => $id]);
        $this->setData($results[0]);
    }
 
    /**
     * getPage [Paginação dos convênios]
     * @param integer $page
     * @param integer $itensPerPage itens por página
     * @return void
     */
    public static function getPage($page = 1, $itensPerPage = 3)
    {
        $start = ($page - 1) * $itensPerPage;

        $sql = new Sql();

        $results = $sql->select(
            "SELECT SQL_CALC_FOUND_ROWS *
            FROM convenios 
            ORDER BY company
            LIMIT $start, $itensPerPage;
        "
        );

        $resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            'data' => $results,
            'total' => (int) $resultTotal[0]['nrtotal'],
            'pages' => ceil($resultTotal[0]['nrtotal'] / $itensPerPage),
        ];
    }
    public static function getPageSearch($search, $page = 1, $itensPerPage = 8)
    {

        $start = ($page - 1) * $itensPerPage;

        $sql = new Sql();

        $results = $sql->select(
            "SELECT SQL_CALC_FOUND_ROWS *
            FROM convenios 
            WHERE company LIKE :search 
            ORDER BY company
            LIMIT $start, $itensPerPage;
        ",
            [
                ':search' => '%' . $search . '%'
            ]
        );

        $resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            'data' => $results,
            'total' => (int) $resultTotal[0]['nrtotal'],
            'pages' => ceil($resultTotal[0]['nrtotal'] / $itensPerPage),
        ];
    }
}
