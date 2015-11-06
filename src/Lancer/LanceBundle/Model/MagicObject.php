<?php
namespace Lancer\LanceBundle\Model;

class MagicObject
{
    private $_data = array();
    private $_originData = array();
    private static $_underscoreCache = array();

    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }

        $result = strtolower(preg_replace("/(.)([A-Z])/", "$1_$2", $name));
        self::$_underscoreCache[$name] = $result;

        return $result;
    }

    public function __call($name, $args)
    {
        $callType = substr($name, 0, 3);
        $key = $this->_underscore(substr($name, 4));
        switch($callType) {
            case 'get':
                return $this->getData($key);
            case 'set':
                return $this->setData($key, $args);
            case 'has':
                return isset($data[$key]);
            case 'uns':
                return $this->unsData($key);
        }
        throw new \Exception("Invalid method ".get_class($this)."::".$name."(".print_r($args,1).")");
    }

    public function getData($key = null)
    {
        if(is_null($key)) {
            return $this->_data;
        }
        return $this->_data[$key];
    }

    public function setData($key, $args = null)
    {
        if (is_array($key)) {
            $this->_data = array_merge($this->_data, $key);
        } elseif (!is_null($args)) {
            $this->_data[$key] = $args;
        }
        return $this;
    }

    public function unsData($key = null)
    {
        if (is_null($key)) {
            $this->_data = array();
        } else {
            unset($this->_data[$key]);
        }

        return $this;
    }

    public function __get($name)
    {

    }
}