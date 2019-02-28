<?php

namespace Sureyee\AreaDB;


use InvalidArgumentException;
use Sureyee\AreaDB\Drivers\LaravelDriver;
use Sureyee\AreaDB\Drivers\PDODriver;

/**
 * Class AreaDB
 * @package Sureyee\AreaDB
 * @method \stdClass get($code)
 */
class AreaDB
{
    protected $config;

    protected $customCreators = [];

    protected $defaultDriver = 'PDO';

    public function __construct(array $config = [])
    {
        if (!isset($config['database'])) {
            $config['database'] = dirname(__FILE__) . '/../db/database.sqlite';
        }
        $this->config = $config;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function resolve(string $name):Driver
    {
        if (isset($this->customCreators[$name])) {
            return $this->callCustomCreator($name);
        }

        $driverMethod = 'create'.ucfirst($name).'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}();
        }

        throw new InvalidArgumentException("Driver {$name} is not defined.");
    }

    public function createLaravelDriver()
    {
        return new LaravelDriver();
    }

    public function createPDODriver()
    {
        return PDODriver::getInstance($this->config);
    }

    protected function callCustomCreator($name)
    {
        return $this->customCreators[$name]($this->config);
    }

    public function extend($driver, \Closure $callback)
    {
        $this->customCreators[$driver] = $callback;
    }

    public function __call($name, $arguments)
    {
        $driver = isset($this->config['driver']) ? $this->config['driver'] : $this->defaultDriver;

        return $this->resolve($driver)->{$name}(...$arguments);
    }
}