<?php

namespace core;


class Model
{
    protected $driver;
    protected $table;
    protected $foreign_key;
    protected $primary_key = 'id';
    private $fields;
    private $where = [];
    private $limit;
    private $offset;
    private $params = [];
    private $orderBy;

    public function __construct()
    {
        $this->driver = new Driver;
    }

    public function get()
    {
        $sql = $this->fields;
        $i = 0;
        foreach ($this->where as $key => $item) {
            if ($i == 0) {
                $sql .= ' WHERE ';
            } else {
                $sql .= ' AND ';
            }
            $sql .= $item['column'] . ' ' . $item['operator'] . ' :' . $item['column'] . $key;

            $this->params[$item['column'] . $key] = $item['value'];
            $i++;
        }
        $sql .= $this->orderBy . $this->limit . $this->offset;
        return $this->driver->row($sql, $this->params);
    }

    public function select($fields = '*')
    {
        $this->fields = 'SELECT ' . $fields . ' FROM ' . $this->table;
        return $this;
    }

    public function where($column, $operator, $value)
    {
        $params = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
        ];
        $this->where[] = $params;
        return $this;
    }


    public function limit($number)
    {
        $this->limit = ' LIMIT :limit ';
        $this->params['limit'] = $number;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = ' OFFSET :offset ';
        $this->params['offset'] = $offset;
        return $this;
    }

    public function orderBy($column, $type = 'ASC')
    {
        $this->orderBy = ' ORDER BY ' . $column . ' ' . $type;
        return $this;
    }

    public function count()
    {
        $result = $this->select('COUNT(' . $this->primary_key . ') AS count')->get();
        $this->select('');
        return $result[0]['count'];
    }



}

















































