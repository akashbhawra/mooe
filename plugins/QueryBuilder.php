<?php
require_once realpath(dirname(__FILE__)) . '/SQLConstructor.php';
class QueryBuilder
{
    const TYPE_SELECT = 'select';
    protected $type;
    protected $select_columns = array();
    protected $tables = array();
    protected $where;
    protected $limit;
    protected $joins;
    protected $groups;
    protected $orders;
    protected $params;
    public static function create()
    {
        return new self();
    }
    public function select($columns)
    {
        $this->type = self::TYPE_SELECT;
        $this->addSelects($columns);
        return $this;
    }
    public function from($tables)
    {
        $this->addFroms($tables);
        return $this;
    }
    public function addSelects($columns)
    {
        $this->select_columns = array_merge($this->select_columns, $this->getAliasedNames($columns));
        return $this;
    }
    public function addFroms($tables)
    {
        $this->tables = array_merge($this->tables, $this->getAliasedNames($tables));
        return $this;
    }
    public function setTable($alias, $table)
    {
        $this->tables[$alias] = $table;
    }
    public function where($expr)
    {
        $this->where = (trim($expr) !== '' ? $expr : null);
        return $this;
    }
    public function join($tables, $join_type, $join_expression)
    {
        $this->joins = array();
        return $this->addJoin($tables, $join_type, $join_expression);
    }
    public function addJoin($tables, $join_type, $join_expression)
    {
        $tables = $this->getAliasedNames($tables);
        $this->joins[] = array('tables' => $tables, 'type' => $join_type, 'expression' => $join_expression);
        return $this;
    }
    public function limit()
    {
        $args = func_get_args();
        if(count($args) == 1)
        {
            $this->limit = $args[0];
        }
        else
        {
            $this->limit = "{$args[0]},{$args[1]}";
        }
        return $this;
    }
    public function group($list)
    {
        $this->groups = $this->getAliasedNames($list);
        return $this;
    }
    public function order($list)
    {
        $this->orders = $this->getAliasedNames($list);
        return $this;
    }
    protected function getAliasedNames($inputs)
    {
        $clean = array();
        if(!is_array($inputs))
        {
            $inputs = explode(',', $inputs);
            foreach($inputs as $input)
            {
                $input = trim($input);
                $clean = array_merge($clean, $this->separateAlias($input));
            }
        }
        else
        {
            $clean = $inputs;
        }
        return $clean;
    }
    protected function separateAlias($name)
    {
        if(strpos($name, ' as ') !== false) // has an 'as' keyword in it
        {
            list($colname, $alias) = explode(' as ', $name, 2);
            return array($alias => $colname);
        }
        elseif(stripos($name, ' ') !== false) // has a space in it
        {
            list($colname, $alias) = explode(' ', $name);
            return array($alias => $colname);
        }
        else
        {
            $colname = $name;
            return array($colname);
        }
    }
    public function __toString()
    {
        return $this->getQuery();
    }
    public function getQuery()
    {
        $sc = new SQLConstructor();
        return $sc->create($this);
    }
    public function getType()
    {
        return $this->type;
    }
    public function getSelectColumns()
    {
        return $this->select_columns;
    }
    public function getTables()
    {
        return $this->tables;
    }
    public function getWhere()
    {
        return $this->where;
    }
    public function getLimit()
    {
        return $this->limit;
    }
    public function getJoins()
    {
        return $this->joins;
    }
    public function getGroups()
    {
        return $this->groups;
    }
    public function getOrders()
    {
        return $this->orders;
    }
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
    public function getParams()
    {
        return $this->params;
    }
    public static function quote($column)
    {
        return "`" . str_replace('.', '`.`', $column) . "`";
    }
}

