<?php
namespace Lancer\LanceBundle\Model;

final class Acl
{
    static private $acl = array();
    static protected $_roles = array();

    public function check($route, $userId)
    {
        if (!isset(self::$acl[$userId]) || !isset(self::$acl[$userId][$route])) {

            $sql = "SELECT * FROM `user`
          INNER JOIN `role` ON `user`.`role_id` = `role`.`id`
          INNER JOIN `role_acl` ON `role_acl`.`role_id` = `role_acl`.`role_id`
          INNER JOIN `acl` ON `role_acl`.`acl_id` = `acl`.`id`
          WHERE `acl`.`route` = :route AND `user`.`id` = :id";

            $sth = DbConnection::getInstance()->getConnection()->prepare($sql);
            $sth->bindParam(":route", $route);
            $sth->bindParam(":id", $userId);
            $sth->execute();

            self::$acl[$userId] = array($route => !empty($sth->fetch()));
        }

        return self::$acl[$userId][$route];
    }

    static public function getRoles()
    {
        if (empty(self::$_roles)) {
            $sth = DbConnection::getInstance()->getConnection()->prepare('SELECT * FROM role');
            $sth->execute();
            self::$_roles = $sth->fetchAll();
        }

        return self::$_roles;
    }
}