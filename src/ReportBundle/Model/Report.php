<?php
namespace ReportBundle\Model;
use Lancer\LanceBundle\Model\DbConnection;

class Report
{
    public static function servicesReport() {
        $data = array();
        $data['services'] = self::_getServicesData();
        $data['profit'] = self::_getServicesProfit();

        return $data;
    }

    protected static function _getServicesData() {
        $sth = DbConnection::getInstance()
            ->getConnection()
            ->prepare('SELECT title, count(*) as total, sum(service.price) as total_price from service INNER JOIN patient_service ON service.id = patient_service.service_id GROUP BY title');
        $sth->execute();
        return $sth->fetchAll();
    }
    protected static function _getServicesProfit() {
        $sth = DbConnection::getInstance()
            ->getConnection()
            ->prepare('SELECT sum(price) as total_price, avg(price) as avg FROM service INNER JOIN patient_service ON service.id = patient_service.service_id ');
        $sth->execute();
        return $sth->fetch();
    }

    public static function diseaseReport() {
        $data['all'] = self::_getDiseasesData();
        $data['month'] = self::_getDiseasesData(true);

        return $data;
    }

    protected static function _getDiseasesData($last = false)
    {
        $connection = DbConnection::getInstance()->getConnection();
        $sql = "SELECT title, count(*) as total FROM disease INNER JOIN patient_disease ON disease.id = patient_disease.disease_id";
        if ($last) {
            $sql .= " WHERE DATEDIFF(CURDATE(), patient_disease.illness_start)<30 ";
        }
        $sql .= " GROUP BY title";
        $sth = $connection->prepare($sql);
        $sth->execute();
        return $sth->fetchAll();
    }
}