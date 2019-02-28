<?php

namespace Sureyee\AreaDB\Drivers;


use Sureyee\AreaDB\Driver;

class PDODriver implements Driver
{

    protected $config;

    protected $pdo;

    private static $instance;

    private function __construct($config)
    {
        $this->config = $config;
    }

    public static function getInstance($config)
    {
        return self::$instance
            ? self::$instance
            : self::$instance = new self($config);
    }

    public function loadPDO()
    {
        if (is_null($this->pdo)) {
            $this->pdo = new \PDO('sqlite:' . $this->config['database']);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return $this->pdo;
    }

    public function get(int $code)
    {
        $stmt = $this->loadPDO()->prepare('SELECT
                                    divisions.id,
                                    divisions.name,
                                    divisions.status,
                                    divisions.year
                                FROM
                                    divisions
                                WHERE
                                    divisions.id = ?');
        $stmt->execute([$code]);
        return $stmt->fetch();
    }
}