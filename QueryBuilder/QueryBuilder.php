<?php

namespace BuildIt\QueryBuilder;

class QueryBuilder
{
    /**
     * @var array $select
     * Array which contains all the fields name of your query SELECT part.
     */
    private $select = [];

    /**
     * @var array $from
     * Array which contains all the tables name of your query FROM part.
     */
    private $from = [];

    /**
     * @var array $select
     * Array which contains all the conditions of your query WHERE part.
     */
    private $where = [];

    /**
     * @var string $orderBy
     * Property which defines by what property your query results are ordered.
     */
    private $orderBy = null;

    /**
     * @var string $order
     * Property which defines in which way your query results are fetched.
     */
    private $order = null;

    /**
     * @var integer $offset
     * Property which defines how many results from your query you want to skip.
     */
    private $offset = null;

    /**
     * @var integer $limit
     * Property which defines how many results you want from your query.
     */
    private $limit = null;

    /**
     * Adds fields name in your query SELECT part.
     * @param array $fields
     * @return $this
     */
    public function select($fields)
    {
        foreach ($fields as $field) {
            $this->select[] = $field;
        }
        return $this;
    }

    /**
     * Adds tables name in your query FROM part.
     * @param array $origins
     * @return $this
     */
    public function from($origins) {
        foreach ($origins as $origin) {
            $this->from[] = $origin;
        }
        return $this;
    }

    /**
     * Adds conditions in your query WHERE part.
     * @param array $conditions
     * @return $this
     */
    public function where($conditions) {
        foreach ($conditions as $condition) {
            $this->where[] = $condition;
        }
        return $this;
    }

    /**
     * Sets your ORDER BY query part.
     * @param string $orderBy
     * @return $this
     */
    public function orderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * Sets your ORDER query part.
     * @param string $order
     * @return $this
     */
    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Sets your OFFSET query part.
     * @param integer $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Sets your LIMIT query part.
     * @param integer $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Returns your query as a string.
     * @return string
     */
    public function getQuery()
    {
        $query = 'SELECT ';
        foreach($this->select as $sel) {
            $query .= $sel;
            if ($sel === end($this->select)) {
                $query .= ' ';
            } else {
                $query .= ', ';
            }
        }

        $query .= 'FROM ';
        foreach($this->from as $fro) {
            $query .= $fro;
            if ($fro === end($this->from)) {
                $query .= ' ';
            } else {
                $query .= ', ';
            }
        }

        if (count($this->where) > 0) {
            $query .= 'WHERE ';
            foreach ($this->where as $whe) {
                $query .= $whe;
                if ($whe === end($this->where)) {
                    $query .= ' ';
                } else {
                    $query .= 'AND ';
                }
            }
        }

        if (!is_null($this->orderBy)) {
            $query .= 'ORDER BY ' . $this->orderBy . ' ';
        }

        if (!is_null($this->order)) {
            $query .= $this->order . ' ';
        }

        if (!is_null($this->limit)) {
            $query .= ' LIMIT ' . $this->limit . ' ';
        }

        if (!is_null($this->offset)) {
            $query .= ' OFFSET ' . $this->offset . ' ';
        }

        return $query;
    }
}
