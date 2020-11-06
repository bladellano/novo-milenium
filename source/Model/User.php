<?php

namespace Source\Model;

use \Source\DB\Sql;
use \Source\Model;

class User extends Model
{

    const SESSION = "User";
    const ERROR = 'UserError';
    const ERROR_REGISTER = 'UserErrorRegister';
    const SUCCESS = "UserSucesss";

    public static function login($login, $password)
    {

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b ON a.idperson = b.idperson WHERE a.deslogin = :LOGIN", array(
            ":LOGIN" => $login
        ));
        if (count($results) === 0) {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }

        $data = $results[0];
        if (password_verify($password, $data["despassword"]) === true) {

            $user = new User();

            $data['desperson'] = utf8_encode($data['desperson']);

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();

            return $user;
        } else {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }
    }


    public static function logout()
    {
        $_SESSION[User::SESSION] = null;
    }

    public function save()
    {
        $sql = new Sql();
        $results = $sql->select(
            "CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin, :idcustomer, :descpf)",
            array(
                ":desperson" => utf8_decode($this->getdesperson()),
                ":deslogin" => $this->getdeslogin(),
                ":despassword" => User::getPasswordHash($this->getdespassword()),
                ":desemail" => $this->getdesemail(),
                ":nrphone" => $this->getnrphone(),
                ":inadmin" => $this->getinadmin(),
                ":idcustomer" => $this->getidcustomer(),
                ":descpf" => $this->getdescpf()
            )
        );

        $this->setData($results[0]);
    }

    public function get($iduser)
    {

        $sql = new Sql();

        $results = $sql->select(
            "SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser",
            array(
                ":iduser" => $iduser,
            )
        );

        $this->setData($results[0]);
    }

    public static function clearErrorRegister()
    {

        $_SESSION[User::ERROR_REGISTER] = NULL;
    }

    public static function checkLoginExist($login)
    {
        $sql = new Sql();
        $results = $sql->select(
            'SELECT * FROM tb_users WHERE deslogin = :deslogin',
            [':deslogin' => $login]
        );
        return (count($results) > 0);
    }

    public static function getErrorRegister()
    {

        $msg = (isset($_SESSION[User::ERROR_REGISTER]) && $_SESSION[User::ERROR_REGISTER]) ? $_SESSION[User::ERROR_REGISTER] : '';

        User::clearErrorRegister();

        return $msg;
    }

    public static function setErrorRegister($msg)
    {

        $_SESSION[User::ERROR_REGISTER] = $msg;
    }

    public static function setError($msg)
    {

        $_SESSION[User::ERROR] = $msg;
    }

    public static function getError()
    {

        $msg = (isset($_SESSION[User::ERROR]) && $_SESSION[User::ERROR]) ? $_SESSION[User::ERROR] : '';

        User::clearError();

        return $msg;
    }

    public static function clearError()
    {

        $_SESSION[User::ERROR] = NULL;
    }

    public static function setSuccess($msg)
    {

        $_SESSION[User::SUCCESS] = $msg;
    }

    public static function getSuccess()
    {

        $msg = (isset($_SESSION[User::SUCCESS]) && $_SESSION[User::SUCCESS]) ? $_SESSION[User::SUCCESS] : '';

        User::clearSuccess();

        return $msg;
    }
    public static function clearSuccess()
    {

        $_SESSION[User::SUCCESS] = NULL;
    }

    public static function getPasswordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT, [
            'cost' => 12
        ]);
    }
}//Fim Classe
