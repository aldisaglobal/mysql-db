<?php

namespace AldisaGlobal\MySQL;

/**
 * DBIterator
 * Abstract Class that implements the Iterator interface
 * Also contains magic accessors to retrieve col values from a loaded row
 * Needs parent class to define firstRow and nextRow methods
 */
abstract class DBIterator implements \Iterator
{

    /**
     * index
     *
     * @var int $index counter for current row
     */
    protected $index;

    /**
     * row
     *
     * @var object|false $row the current row object or false if EOF
     */
    protected $row;

    /**
     * getRow
     * get the next row in the ResultSet or false at EOF
     *
     * @return object|bool
     */
    abstract public function getNextRow();

    /**
     * reset
     * get first row of ResultSet
     *
     * @return object|bool
     */
    abstract public function getFirstRow();

    /**
     * init
     * sets row and index containers to null
     *
     * @return void
     */
    public function init()
    {
        $this->row = null;
        $this->index = null;
    }

    /**
     * current
     * returns the current row object
     *
     * @return object|bool
     */
    public function current()
    {
        if (is_null($this->row)) {
            throw new \Exception("DB Error: There is no result");
        }

        return $this->row;
    }

    /**
     * key
     * return the current index counter
     *
     * @return int
     */
    public function key()
    {
        if (is_null($this->row)) {
            throw new \Exception("DB Error: There is no result");
        }

        return $this->index;
    }

    /**
     * next
     * loads next row and increments counter
     *
     * @return void
     */
    public function next()
    {
        if (is_null($this->row)) {
            throw new \Exception("DB Error: There is no result");
        }
        if (false === $this->row) {
            throw new \Exception("DB Error: Already at end of results");
        }

        $this->index++;
        $this->row = $this->getNextRow();
    }

    /**
     * rewind
     * returns to first row of results
     *
     * @return void
     */
    public function rewind()
    {
        if (is_null($this->row)) {
            throw new \Exception("DB Error: There is no result");
        }

        $this->index = 0;
        $this->row = $this->getFirstRow();
    }

    /**
     * valid
     * returns false if row contains false or true otherwise
     *
     * @return bool
     */
    public function valid()
    {
        if (is_null($this->row)) {
            throw new \Exception("DB Error: There is no result");
        }

        return (false === $this->row ? false : true);
    }

    /**
     * __get
     * magic method to access values in the row object
     *
     * @param  string $key the column name
     * @return mixed
     */
    public function __get($column)
    {
        if (is_null($this->row)) {
            throw new \Exception("DB Error: There is no result");
        }

        if (false === $this->row || !property_exists($this->row, $column)) {
            return null;
        }

        return $this->row->$column;
    }
}
