<?php

namespace BuildIt\Database;

use \PDO;

/**
 * Class Database
 * @package BuildIt\Database
 */
abstract class Database
{
    /**
     * @var array $config
     * Array where Database connection parameters are stored.
     */
    protected $config = [
        'db_host' => 'localhost',
        'db_name' => 'buildit',
        'db_user' => 'root',
        'db_pass' => 'root'
    ];

    /**
     * @var \PDO $pdo
     * PDO object where Database connection will be stored.
     */
    private $pdo = null;

    /**
     * Database constructor.
     */
    protected function __construct()
    {
        $this->setPdo();
    }

    /**
     * Sets your $pdo to an active state linked to a MYSQL bridge.
     */
    private function setPdo() {
        $this->pdo = new PDO(
            'mysql:dbname=' . $this->config['db_name'] . ';host=' . $this->config['db_host'],
            $this->config['db_user'],
            $this->config['db_pass']
        );
    }

    /**
     * Takes a SQL $query with binding $params and returns an array of objects or a single object depending on $fetchAll value.
     * @param string $query
     * @param boolean $fetchAll
     * @param array $params
     * @return mixed
     */
    public function query($query, $fetchAll, $params = [])
    {
        $result = $this->pdo->prepare($query);
        foreach ($params as $k => $v) {
            $result->bindValue(':' . $k, $v);
        }
        $result->execute();
        $result->setFetchMode(\PDO::FETCH_OBJ);
        return $fetchAll ? $result->fetchAll() : $result->fetch();
    }

    /**
     * Returns the last inserted ID in the Database.
     * @return string
     */
    public function getLastInsertedId()
    {
        return $this->pdo->lastInsertId();
    }
}
