<?php
namespace Lancer\LanceBundle\Model;

class User extends AbstractModel
{
    public function __construct($data = null)
    {
        parent::__construct('user', 'id');
        if (!is_null($data)) {
            $this->setData($data);
            $this->setOriginData($data);
        }
    }

    public function getSecretHash()
    {
        $result = '';
        if ($this->getId()) {
            $sth = DbConnection::getInstance()->getConnection()->prepare("SELECT `hash` FROM `secret` WHERE `user_id` = ?");
            $sth->execute(array($this->getId()));
            $result = $sth->fetch();
            if (!empty($result)) {
                $result = $result['hash'];
            }
        }
        return $result;
    }

    public function loadByHash($hash)
    {
        if (!empty($hash)) {
            $sth = DbConnection::getInstance()->getConnection()->prepare("SELECT user_id FROM `secret` WHERE hash=?");
            $sth->execute(array($hash));
            $row = $sth->fetch();
            if (!empty($row)) {
                $this->load($row['user_id']);
            }
        }
        return $this;
    }

    public function transfer()
    {
        try {
        $sth = DbConnection::getInstance()->getConnection()->prepare("CALL UserTransfer(?)");
        $sth->execute(array($this->getId()));
        $this->setPatientId(DbConnection::getInstance()->getConnection()->lastInsertId());
        } catch(\Exception $e) {
            echo 'TRANSFER REJECTED.';
            die();
        }
    }

}