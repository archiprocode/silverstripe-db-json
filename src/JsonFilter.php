<?php

namespace ArchiPro\Silverstripe\DbJson;

use SilverStripe\ORM\DataQuery;
use SilverStripe\ORM\DB;
use InvalidArgumentException;
use SilverStripe\ORM\Filters\SearchFilter;

/**
 * Matches textual content with a LIKE '%keyword%' construct.
 */
class JsonFilter extends SearchFilter
{

    public function getSupportedModifiers()
    {
        return ['not'];
    }

    /**
     * Apply the match filter to the given variable value
     *
     * @param string $value The raw value
     * @return string
     */
    protected function getMatchPattern($value)
    {
        return "%$value%";
    }

    /**
     * Apply filter criteria to a SQL query.
     *
     * @param DataQuery $query
     * @return DataQuery
     */
    public function apply(DataQuery $query)
    {
        if ($this->aggregate) {
            throw new InvalidArgumentException(sprintf(
                'Aggregate functions can only be used with comparison filters. See %s',
                $this->fullName
            ));
        }

        return parent::apply($query);
    }

    protected function applyOne(DataQuery $query)
    {
        throw new \RuntimeException('Not implemented');
    }

    protected function applyMany(DataQuery $query)
    {
        $this->model = $query->applyRelation($this->relation);
        $field = $this->getDbName();
        $values = $this->getValue();

        if (empty($values)) {
            throw new InvalidArgumentException(sprintf(
                'Filtering by an empty array is not supported. See %s',
                $this->fullName
            ));
        }

        if (!is_array($values)) {
            throw new InvalidArgumentException(sprintf(
                'Must provide value as array when filtering by JSON_EXTRACT. See %s',
                $this->fullName
            ));
        }

        $whereClause = sprintf('JSON_CONTAINS(%s, ?, ?)', $field);
        $wheres = [];

        foreach ($values as $path => $value) {
            $wheres[] = [$whereClause => [json_encode($value), $path]];
        }

        return $query->whereAny($wheres);
    }

    protected function excludeOne(DataQuery $query)
    {
        throw new \RuntimeException('Not implemented');
    }

    protected function excludeMany(DataQuery $query)
    {
        $this->model = $query->applyRelation($this->relation);
        $field = $this->getDbName();
        $values = $this->getValue();

        if (empty($values)) {
            throw new InvalidArgumentException(sprintf(
                'Filtering by an empty array is not supported. See %s',
                $this->fullName
            ));
        }

        if (!is_array($values)) {
            throw new InvalidArgumentException(sprintf(
                'Must provide value as array when filtering by JSON_EXTRACT. See %s',
                $this->fullName
            ));
        }

        $whereClause = sprintf('NOT JSON_CONTAINS(%s, ?, ?)', $field);
        $wheres = [];

        foreach ($values as $path => $value) {
            $wheres[] = [$whereClause => [json_encode($value), $path]];
        }

        return $query->where($wheres);
    }

    public function isEmpty()
    {
        return empty($this->getValue());
    }
}
