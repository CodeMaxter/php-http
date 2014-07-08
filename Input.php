<?php

/**
 * Description of Input
 *
 * @author Alvaro José Agámez Licha - CodeMaxter
 */
class Input
{

    static private $params = null;

    /**
     * @brief Lookup request params
     * @param string $name Name of the argument to lookup
     * @param mixed $default Default value to return if argument is missing
     * @returns The value from the GET/POST/PUT/DELETE value, or $default if not set
     */
    static public function get($name = null, $default = null)
    {
        if (null === self::$params) {
            self::parseParams();
        }

        if (null === $name) {
            return self::$params;
        }

        if (isset(self::$params[$name])) {
            return self::$params[$name];
        }

        return $default;
    }

    static protected function parseParams()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                self::$params = $_GET;
                break;
            case 'PUT':
            case 'DELETE':
                parse_str(file_get_contents('php://input'), self::$params);
                break;
            case 'POST':
                self::$params = $_POST;
                break;
        }
    }

}
