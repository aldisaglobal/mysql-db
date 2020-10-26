<?php

namespace Aldisa\MySQL;

class Browser implements \Iterator
{
    /**
     * mysqli connection object
     *
     * @var object
     */
    private $conn;

    /**
     * mysqli result object
     *
     * @var object
     */
    private $result;
    
    /**
     * Instanstiate using mysqli connection object
     *
     * @param  mysqli $conn
     * @return void
     */
    public function __construct(\mysqli $conn)
    {
        $this->conn = $conn;
        $this->result = null;
    }

    /**
     * Escape string using conn
     *
     * @param  string $str
     * @return string
     */
    public function escape($str)
    {
        return $this->conn->real_escape_string($str);
    }

    /**
     * Return info prop from conn
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->conn->info;
    }

     /**
     * Return error prop from conn
     *
     * @return string
     */
    public function getError()
    {
        return $this->conn->error;
    }

    /**
     * Return errno prop from conn
     *
     * @return int
     */
    public function getErrno()
    {
        return $this->conn->errno;
    }

    /**
     * Return true/false based on errno
     *
     * @return bool
     */
    public function hasError()
    {
        return ($this->conn->errno === 0 ? false : true);
    }

   /**
     * Executes SQL Query
     *
     * @param  string $sql
     * @return bool
     */
    public function query($sql)
    {
        $this->result = $this->conn->query($sql);

        if (false === $this->result) {
            throw new \Exception("Database Error: ({$this->conn->errno}) {$this->conn->error}");
        }

        return true;
    }

    /**
     * True if result is instanceof mysqli_result
     *
     * @return bool
     */
    public function hasResult()
    {
        return ($this->result instanceof \mysqli_result);
    }

  /**
     * Return insert_id prop from conn
     *
     * @return int
     */
    public function getInsertID()
    {
        return $this->conn->insert_id;
    }

    /**
     * Fetch row from result
     *
     * @return mixed
     */
    public function getRow($mode = "object")
    {
        if (!($this->result instanceof \mysqli_result)) throw new \Exception("DB Error: There is no result");
        return ($mode == "array" ? $this->result->fetch_array() : $this->result->fetch_object());
    }

    /**
     * Return specific row from result
     *
     * @param  int $num
     * @return bool
     */
    public function getRowNum($num, $mode = "object")
    {
        if (!($this->result instanceof \mysqli_result)) throw new \Exception("DB Error: There is no result");
        
        $found = $this->result->data_seek($num);
        if (false === $found) return false;

        return ($mode == "array" ? $this->result->fetch_array() : $this->result->fetch_object());
    }

    /**
     * Return field_count prop from result
     *
     * @return int
     */
    public function getNumFields()
    {
        if (!($this->result instanceof \mysqli_result)) throw new \Exception("DB Error: There is no result");
        return $this->result->field_count;
    }

    /**
     * Alias for fetch_fields on result
     *
     * @return array
     */
    public function getFields()
    {
        if (!($this->result instanceof \mysqli_result)) throw new \Exception("DB Error: There is no result");
        return $this->result->fetch_fields();
    }

    /**
     * Return num_rows prop from result
     *
     * @return int
     */
    public function getNumRows()
    {
        if (!($this->result instanceof \mysqli_result)) throw new \Exception("DB Error: There is no result");
        return $this->result->num_rows;
    }

    /* Iterator Interface Implemntation */

    private $index;
    private $row;

    public function current()
    {
        if (false === $this->hasResult()) throw new \Exception("DB Error: There is no result");
        return $this->row;
    }

    public function key()
    {
        if (false === $this->hasResult()) throw new \Exception("DB Error: There is no result");
        return $this->index;
    }

    public function next()
    {
        if (false === $this->hasResult()) throw new \Exception("DB Error: There is no result");
        $this->index++;
        $this->row = $this->getRow();
    }

    public function rewind()
    {
        if (false === $this->hasResult()) throw new \Exception("DB Error: There is no result");
        $this->index = 0;
        $this->result->data_seek(0);
        $this->row = $this->getRow();
    }

    public function valid()
    {
        if (false === $this->hasResult()) throw new \Exception("DB Error: There is no result");
        return $this->index < $this->result->num_rows;
    }
}
