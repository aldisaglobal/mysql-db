<?php

namespace AldisaGlobal\MySQL;

class DB extends DBIterator
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
     * buffered query mode
     *
     * @var bool
     */
    private $buffered;

    /**
     * Static Factory method that inits connection and returns object instance
     *
     * @param  mixed $config
     * @return void
     */
    public static function create(array $config = []): DB
    {
        $params = array(
            'MYSQL_HOST' => "",
            'MYSQL_USER' => "",
            'MYSQL_PASS' => "",
            'MYSQL_DB' => "",
        );

        $params = array_intersect_key(array_merge($_ENV, get_defined_constants(), $config), $params);
        if (count($params) < 4) {
            throw new \Exception("DB Error: Missing Params");
        }

        $conn = @new \mysqli($params['MYSQL_HOST'], $params['MYSQL_USER'], $params['MYSQL_PASS'], $params['MYSQL_DB']);
        if ($conn->connect_errno > 0) {
            throw new \Exception("DB Error: ({$conn->connect_errno}) {$conn->connect_error}");
        }

        $db = new DB($conn);
        return $db;
    }

    /**
     * Instanstiate using mysqli connection object
     *
     * @param  mysqli $conn
     * @return void
     */
    public function __construct(\mysqli $conn)
    {
        $this->conn = $conn;
        $this->init();
    }

    /**
     * init
     * resets query state and release previous result
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->buffered = true;
        if ($this->result instanceof \mysqli_result) {
            $this->result->close();
        }
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
    public function query($sql, $buffered = true)
    {
        $this->init();

        $this->buffered = (false === $buffered ? false : true);
        $resultmode = ($this->buffered ? MYSQLI_STORE_RESULT : MYSQLI_USE_RESULT);
        $this->result = $this->conn->query($sql, $resultmode);

        if (false === $this->result) {
            throw new \Exception("Database Error: ({$this->conn->errno}) {$this->conn->error}");
        }

        if ($this->hasResult()) {
            $this->index = 0;
            $this->row = $this->getNextRow();
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

    public function getRow()
    {
        return $this->current();
    }
    /**
     * Fetch next row from result
     *
     * @param  string $mode
     * @return mixed
     */
    public function getNextRow($mode = "object")
    {
        if (!$this->hasResult()) {
            throw new \Exception("DB Error: There is no result");
        }

        $row = ($mode == "array" ? $this->result->fetch_array() : $this->result->fetch_object());
        return (is_null($row) ? false : $row);
    }

    /**
     * fetch the first Row
     *
     * @param  string $mode
     * @return mixed
     */
    public function getFirstRow($mode = "object")
    {
        return $this->getRowNum(0, $mode);
    }

    /**
     * Return specific row from result
     *
     * @param  int $num
     * @return bool
     */
    public function getRowNum($num, $mode = "object")
    {
        if (!$this->hasResult()) {
            throw new \Exception("DB Error: There is no result");
        }

        $found = $this->result->data_seek($num);
        if (false === $found) {
            return false;
        }

        return ($mode == "array" ? $this->result->fetch_array() : $this->result->fetch_object());
    }

    /**
     * Return field_count prop from result
     *
     * @return int
     */
    public function getNumFields()
    {
        if (!$this->hasResult()) {
            throw new \Exception("DB Error: There is no result");
        }

        return $this->result->field_count;
    }

    /**
     * Alias for fetch_fields on result
     *
     * @return array
     */
    public function getFields()
    {
        if (!$this->hasResult()) {
            throw new \Exception("DB Error: There is no result");
        }

        return $this->result->fetch_fields();
    }

    /**
     * Return num_rows prop from result
     *
     * @return int
     */
    public function getNumRows()
    {
        if (!$this->hasResult()) {
            throw new \Exception("DB Error: There is no result");
        }

        return $this->result->num_rows;
    }

}
