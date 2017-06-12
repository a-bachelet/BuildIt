<?php

namespace BuildIt\Model;

use BuildIt\Database\Database;
use BuildIt\QueryBuilder\QueryBuilder;
use BuildIt\Repository\Repository;

/**
 * Class Model
 * @package BuildIt\Model
 */
abstract class Model
{
    /**
     * @var integer $id
     * Identifier of the entity in the database.
     */
    private $id;

    /**
     * Get id.
     * @return string | integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set id.
     * @param string | integer $id
     */
    private function setId($id) {
        $this->id = $id;
    }

    /**
     * Fills the entity properties with $values array of properties from database.
     * @param array $values
     */
    public function fill($values = []) {
        foreach ($values as $key => $value) {
            $key = explode('_', $key);
            if (count($key) > 1 && end($key) === 'id') {
                array_pop($key);
            }
            $key = array_values($key);
            for ($i = 0; $i < count($key); $i++) {
                $key[$i] = ucfirst($key[$i]);
            }
            $key = implode('', $key);
            $func = 'set' . $key;
            if (method_exists($this, $func)) {
                $this->$func($value);
            }
        }
        $this->afterFill();
    }

    /**
     * Function called after the entity is filled.
     */
    protected function afterFill() {}

    /**
     * Retrieves entities from database about a ManyToMany relationship thanks to lower params.
     * @param Database $db
     * @param string $join_table
     * @param string $wProp
     * @param string $oProp
     * @param string | integer $value
     * @param Repository $repo
     * @return Model[]
     */
    protected function manyToMany($db, $join_table, $wProp, $oProp, $value, $repo) {
        $qb = new QueryBuilder();
        $repo = new $repo();
        $query = $qb
            ->select([$wProp])
            ->from([$join_table])
            ->where([$oProp . ' = ' . $value])
            ->getQuery();
        $result = $db->query($query, true);
        $entities = [];
        foreach ($result as $k => $v) {
            foreach ($v as $k => $v) {
                $entity = $repo->find($v);
                $entities[] = $entity;
            }
        }
        return $entities;
    }
}
