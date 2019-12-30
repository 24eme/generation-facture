<?php

namespace App\Database\Connector;

class SQLite implements DatabaseInterface
{
    /** @var $connector The \PDO connector */
    protected $connector;

    /**
     * Constructor
     *
     * @param string $database_path Path to DB file
     */
    public function __construct(string $database_path)
    {
        if (! extension_loaded('pdo_sqlite') || ! extension_loaded('pdo'))
        {
            throw new \Exception('Missing database extension');
        }

        if (! file_exists($database_path)) {
            throw new \Exception($database_path . ' does not exist');
        }

        $this->connector = new \PDO('sqlite:'.$database_path);
    }

    /**
     * Return the connector
     *
     * @return \PDO The connector
     */
    public function getConnector()
    {
        return $this->connector;
    }
}
