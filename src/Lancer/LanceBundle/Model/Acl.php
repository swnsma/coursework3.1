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

    static public function getRoutes()
    {
        $sth = DbConnection::getInstance()->getConnection()->prepare('SELECT * FROM acl');
        $sth->execute();
        return $sth->fetchAll();
    }

    static public function getAclList()
    {
        $sql = "SELECT role.id as role, role.title, acl.id as acl, acl.route  FROM role LEFT JOIN role_acl ON role.id = role_acl.role_id
                LEFT JOIN acl ON acl.id = role_acl.acl_id";
        $sth = DbConnection::getInstance()->getConnection()->prepare($sql);
        $sth->execute();
        $list = array();
        while ($data = $sth->fetch()) {
            if (empty($list[$data['role']])) {
                $list[$data['role']] = array('title' => $data['title']);
                $list[$data['role']]['acl'] = array();
                $list[$data['role']]['acl'][$data['acl']] = $data['route'];
            } else {
                $list[$data['role']]['acl'][$data['acl']] = $data['route'];
            }
        }

        return $list;
    }

    static public function saveRole($data)
    {
        if ($data['id']) {
            $sql = "UPDATE role set role.title = :title WHERE id = :id";
        } else {
            $sql = "INSERT INTO role (title) VALUES(:title)";
        }

        $sth = DbConnection::getInstance()->getConnection()->prepare($sql);
        $sth->execute($data);

        if (!$data['id']) {
            return DbConnection::getInstance()->getConnection()->lastInsertId();
        }

        return $data['id'];
    }

    static public function saveAccess($data)
    {
        if($data['allow']=="true") {
            $sql = "INSERT INTO role_acl (role_id, acl_id) VALUES(:role, :route)";
        } else {
            $sql = "DELETE FROM role_acl WHERE role_id= :role AND acl_id= :route";
        }
        unset($data['allow']);
        $sth = DbConnection::getInstance()->getConnection()->prepare($sql);
        $sth->execute($data);
    }
}