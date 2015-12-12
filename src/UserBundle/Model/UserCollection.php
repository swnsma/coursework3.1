<?php

namespace UserBundle\Model;

use Lancer\LanceBundle\Model\AbstractCollection;
use Lancer\LanceBundle\Model\DbConnection;
use Lancer\LanceBundle\Model\User;


class UserCollection extends AbstractCollection
{
    public function __construct() {
        parent::__construct('user');
    }

    public function getAllItems()
    {
        if (empty($this->_items)) {
            $data = $this->getAllItemsData();
            foreach ($data as $item) {
                $this->_items[] = new User($item);
            }
        }

        return $this->_items;
    }

    public function getAllItemsData()
    {
        if (empty($this->_itemsData)) {
            $this->_load();
        }

        return $this->_itemsData;
    }

    protected function _load() {
        $connection = DbConnection::getInstance()->getConnection();
        $sth = $connection->query('SELECT user.id, name, second_name, photo, email, title, role.id as role  FROM user INNER JOIN role on user.role_id = role.id');
        $sth->execute();
        $this->_itemsData = $sth->fetchAll();
    }
}