<?php
namespace Lancer\LanceBundle\Model;

class User extends AbstractModel
{
    public function __construct($data = null)
    {
        parent::__construct('user', 'id');
        if (!is_null($data)) {
            $this->setData($data);
        }
    }

    public function checkExists()
    {
        if (!empty($this->getData('id'))) {
            return true;
        }

        if (!empty($this->getData('email'))) {
            $sth = DbConnection::getInstance()->getConnection()->prepare("SELECT $this->_primary FROM $this->_mainTable WHERE `email` = ?");
            $sth->execute(array($this->getData('email')));
            $result = $sth->fetch();
            if (!empty($result)) {
                return true;
            }
        }
        return false;
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

}