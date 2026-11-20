<?php
class MysqlHelper {
    private $conn;

    public function __construct() {
        $host = 'localhost';
			$db = 'planitlo_crm';
			$user = 'root';
			// $pass = 'Securel0gi!12aa';
			$pass = 'Root765%^&';
		$this->conn = mysql_connect($host, $user, $pass) or die('Could not connect to the server: '.mysql_error());

        if (!mysql_select_db($db, $this->conn)) {
            die('Database selection failed: ' . mysql_error());
        }
    }

    public function query($sql) {
        $result = mysql_query($sql, $this->conn);
        if (!$result) {
            die('Query failed: ' . mysql_error());
        }
        return $result;
    }

    public function fetchAll($sql) {
        $result = $this->query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function fetchOne($sql) {
        $result = $this->query($sql);
        return mysql_fetch_assoc($result);
    }

    public function escape($value) {
        return mysql_real_escape_string($value, $this->conn);
    }

    public function insert($table, $data) {
        $fields = array();
        $values = array();

        foreach ($data as $key => $value) {
            $fields[] = "`" . $this->escape($key) . "`";

            if (is_null($value)) {
                $values[] = "NULL";
            } else {
                $values[] = "'" . $this->escape($value) . "'";
            }
        }

        $sql = "INSERT INTO `$table` (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $values) . ")";
        return $this->query($sql);
    }

    public function update($table, $data, $where) {
        $updates = array();

        foreach ($data as $key => $value) {
            if (is_null($value)) {
                $updates[] = "`" . $this->escape($key) . "` = NULL";
            } else {
                $updates[] = "`" . $this->escape($key) . "` = '" . $this->escape($value) . "'";
            }
        }

        $whereClause = $this->buildWhere($where);

        $sql = "UPDATE `$table` SET " . implode(", ", $updates) . " WHERE $whereClause";
        return $this->query($sql);
    }

    public function delete($table, $where) {
        $whereClause = $this->buildWhere($where);
        $sql = "DELETE FROM `$table` WHERE $whereClause";
        return $this->query($sql);
    }

    public function buildWhere($where) {
        if (is_array($where)) {
            $clauses = array();
            foreach ($where as $key => $value) {
                if (is_null($value)) {
                    $clauses[] = "`" . $this->escape($key) . "` IS NULL";
                } else {
                    $clauses[] = "`" . $this->escape($key) . "` = '" . $this->escape($value) . "'";
                }
            }
            return implode(" AND ", $clauses);
        } else {
            return $where; // fallback for string where clause
        }
    }

    public function close() {
        mysql_close($this->conn);
    }
}
?>
