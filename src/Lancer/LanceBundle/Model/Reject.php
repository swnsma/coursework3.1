<?php
namespace Lancer\LanceBundle\Model;
class Reject
{
    public static function getRejects() {
        $sth = DbConnection::getInstance()->getConnection()->query('SELECT * FROM rejection');
        return $sth->fetchAll();
    }

    public static function saveReject($reject) {
        $sth = DbConnection::getInstance()->getConnection()->prepare('UPDATE rejection SET status=:status WHERE code=:code');
        $sth->execute($reject);
    }
}