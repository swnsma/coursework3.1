<?php
namespace Lancer\LanceBundle\Model;

abstract class MagicObject
{
    private $_data = array();
    private $_originData = array();
    private static $_underscoreCache = array();

    /**
     * Change requested field name to underscore type.
     *
     * @param $name
     *
     * @return string
     */
    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }

        $result                        = strtolower(preg_replace("/(.)([A-Z])/", "$1_$2", $name));
        self::$_underscoreCache[$name] = $result;

        return $result;
    }

    public function __call($name, $args)
    {
        $callType = substr($name, 0, 3);
        $key      = $this->_underscore(substr($name, 3));
        switch ($callType) {
            case 'get':
                return $this->getData($key);
            case 'set':
                return $this->setData($key, $args[0] ? $args[0] : null);
            case 'has':
                return isset($data[$key]);
            case 'uns':
                return $this->unsData($key);
        }
        throw new \Exception("Invalid method " . get_class($this) . "::" . $name . "(" . print_r($args, 1) . ")");
    }

    public function getData($key = null)
    {
        if (is_null($key)) {
            return $this->_data;
        }

        return $this->_data[$key];
    }

    public function setData($key, $value = null)
    {
        if (is_array($key)) {
            $this->_data = array_merge($this->_data, $key);
        } else {
            $this->_data[$key] = $value;
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

    public function __set($name, $value)
    {
        $var = $this->_underscore($name);

        return $this->setData($var, $value);
    }

    public function __get($name)
    {
        $var = $this->_underscore($name);

        return $this->getData($var);
    }

    /**
     * Set origin data.
     *
     * @param      $key
     * @param null $value
     */
    public function setOriginData($key, $value = null)
    {
        if (is_array($key)) {
            $this->_originData = $key;
        } else {
            $this->_originData[$key] = $value;
        }
    }

    /**
     * Return origin data.
     *
     * @param null $key
     *
     * @return array
     */
    public function getOriginData($key = null)
    {
        if (is_null($key)) {
            return $this->_originData;
        } else {
            return $this->_originData[$key];
        }
    }

    /**
     * Detect data changing.
     *
     * @param $key
     *
     * @return bool
     */
    public function dataChanged($key)
    {
        if ($this->_data[$key] != $this->_originData[$key]) {
            return false;
        }

        return true;
    }
}