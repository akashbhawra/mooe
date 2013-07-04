<?php
class SQLConstructor
{
    protected $qb;
    public function create(QueryBuilder $qb)
    {
        $this->qb = $qb;
        switch($qb->getType())
        {
            case QueryBuilder::TYPE_SELECT:
                return $this->createSelect();
                break;
        }
    }
    protected function createSelect()
    {
        $sql = 'SELECT ';
        $sql .= $this->getColumns($this->qb->getSelectColumns());
        $sql .= "\n FROM ";
        $sql .= $this->getColumns($this->qb->getTables());
        if(null !== $joins = $this->qb->getJoins())
        {
            foreach($joins as $join)
            {
                $sql .= "\n JOIN ";
                $sql .= $this->getColumns($join['tables']);
                $sql .= " {$join['type']} ";
                $sql .= $join['expression'];
            }
        }
        if(null !== $where = $this->qb->getWhere())
        {
            $sql .= "\n WHERE ";
            $sql .= (string)$where;
        }
        if(null !== $group = $this->qb->getGroups())
        {
            $sql .= "\n GROUP BY ";
            $sql .= $this->getColumns($group);
        }
        if(null !== $orders = $this->qb->getOrders())
        {
            $sql .= "\n ORDER BY ";
            $sql .= $this->getColumns($orders);
        }
        if(null !== $limit = $this->qb->getLimit())
        {
            $sql .= "\n LIMIT $limit";
        }
        
        return $sql;
    }
    protected function getColumns($columns)
    {
        $clean = array();
        foreach($columns as $alias => $column)
        {
            if(!is_int($alias))
            {
                $clean[] = $this->prepareColumn($column) . " as $alias";
            }
            else
            {
                if((!is_scalar($column)) || (is_scalar($column) && $column == '*'))
                {
                    $clean[] = $this->prepareClean($column);
                }
                else 
                {
                    $clean[] = $this->prepareColumn($column);
                }
            }
        }
        return implode(',', $clean);
    }
    protected function prepareColumn($column)
    {
        return "" . str_replace('.', '.', $column) . "";
    }
    protected function prepareClean($column)
    {
        return $column;
    }
}