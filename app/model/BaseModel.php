<?php
class BaseModel {
    protected $conn;
    protected $table;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function findAll() {
        $query = "SELECT * FROM {$this->table}";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function findById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = " . (int)$id;
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }
    
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_map([$this, 'escape'], array_values($data))) . "'";
        
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";
        return mysqli_query($this->conn, $query);
    }
    
    public function update($id, $data) {
        $sets = [];
        foreach ($data as $column => $value) {
            $sets[] = "{$column} = '" . $this->escape($value) . "'";
        }
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = " . (int)$id;
        return mysqli_query($this->conn, $query);
    }
    
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = " . (int)$id;
        return mysqli_query($this->conn, $query);
    }
    
    protected function escape($value) {
        return mysqli_real_escape_string($this->conn, $value);
    }
    
    protected function query($sql) {
        return mysqli_query($this->conn, $sql);
    }
} 