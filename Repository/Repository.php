<?php

namespace BuildIt\Repository;

use BuildIt\Database\Database;
use BuildIt\Model\Model;
use BuildIt\QueryBuilder\QueryBuilder;

abstract class Repository
{

    /**
     * @var string $table
     * Name of the database table where entities should be retrieved.
     */
    protected $table = null;

    /**
     * @var Model $model
     * Model class which represents retrieved entities.
     */
    protected $model = null;

    /**
     * @var Database $db
     * Database Object from which entities should be called.
     */
    protected $db = null;

    /**
     * Returns the entity which have the $id id in the table.
     * @param string | integer $id
     * @return mixed
     */
    public function find($id) {
        $qb = new QueryBuilder();
        $query = $qb
            ->select(['*'])
            ->from([$this->table])
            ->where(['id = :id'])
            ->getQuery();
        $result = $this->db->query($query, false, ['id' => $id]);
        $entity = new $this->model();
        $entity->fill($result);
        return $entity;
    }

    /**
     * Returns all the entities in the table.
     * @return array
     */
    public function findAll() {
        $qb = new QueryBuilder();
        $query = $qb
            ->select(['*'])
            ->from([$this->table])
            ->getQuery();
        $result = $this->db->query($query, true);
        $entities = [];
        foreach ($result as $res) {
            $entity = new $this->model();
            $entity->fill($res);
            $entities[] = $entity;
        }
        return $entities;
    }

    /**
     * Returns the number of entities inside the table.
     * @return int
     */
    public function count() {
        $qb = new QueryBuilder();
        $query = $qb
            ->select(['COUNT(*) as count'])
            ->from([$this->table])
            ->getQuery();
        $result = $this->db->query($query, false)->count;
        return intval($result);
    }

    /**
     * Returns the last $quantity entities from the table.
     * @param integer | string $quantity
     * @return array
     */
    public function findLast($quantity) {
        $qb = new QueryBuilder();
        $query = $qb
            ->select(['*'])
            ->from([$this->table])
            ->orderBy('id')
            ->order('DESC')
            ->limit(intval($quantity))
            ->getQuery();
        $result = $this->db->query($query, true);
        $entities = [];
        foreach ($result as $res) {
            $entity = new $this->model();
            $entity->fill($res);
            $entities[] = $entity;
        }
        return $entities;
    }

    /**
     * Returns entities in the table from $offset position to $offset + $limit position.
     * @param integer | string $offset
     * @param integer | string $limit
     * @param string $order
     * @return array
     */
    public function findBetween($offset, $limit, $order = 'ASC') {
        $qb = new QueryBuilder();
        $query = $qb
            ->select(['*'])
            ->from([$this->table])
            ->orderBy('id')
            ->order($order)
            ->limit($limit)
            ->offset($offset)
            ->getQuery();
        $result = $this->db->query($query, true);
        $entities = [];
        foreach ($result as $res) {
            $entity = new $this->model();
            $entity->fill($res);
            $entities[] = $entity;
        }
        return $entities;
    }

    /**
     * Returns entities in the table which have props which corresponds to $props.
     * @param array $props
     * @return array
     */
    public function findBy($props = []) {
        $qb = new QueryBuilder();
        $qb->select(['*'])->from([$this->table]);
        $conditions = [];
        foreach ($props as $k => $v) {
            if (is_string($v)) {
                $conditions[] = $k . ' = ' . '\'' . $v . '\'';
            } else {
                $conditions[] = $k . ' = ' . $v;
            }
        }
        $qb->where($conditions);
        $query = $qb->getQuery();
        $result = $this->db->query($query, true);
        $entities = [];
        foreach ($result as $res) {
            $entity = new $this->model();
            $entity->fill($res);
            $entities[] = $entity;
        }
        return $entities;
    }

}
