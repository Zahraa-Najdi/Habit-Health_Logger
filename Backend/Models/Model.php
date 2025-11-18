<?php

abstract class Model
{
    protected static string $table;

    public function __construct($data)
    {
    }

    //Create record
    public static function create(mysqli $connection, array $data)
    {
        $table = static::$table;
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $types = self::getAllTypes($data);
        $values = array_values($data);

        $stmt = $connection->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
        if (!$stmt) return false;

        $stmt->bind_param($types, ...$values);
        if ($stmt->execute()) {
            return $connection->insert_id;
        } else {
            if ($connection->errno === 1062) return "Duplicate";
            return false;
        }
    }

    //Update record by primary key
    public static function update(mysqli $connection, int $id, array $data, string $primary_key = "id")
    {
        $table = static::$table;
        $set = implode(", ", array_map(fn($k) => "$k = ?", array_keys($data)));
        $types = self::getAllTypes($data) . "i";
        $values = array_values($data);
        $values[] = $id;

        $stmt = $connection->prepare("UPDATE $table SET $set WHERE $primary_key = ?");
        if (!$stmt) return false;

        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    //Find all records
    public static function findAll(mysqli $connection)
    {
        $table = static::$table;
        $result = $connection->query("SELECT * FROM $table");
        $objects = [];
        while ($row = $result->fetch_assoc()) {
            $objects[] = new static($row);
        }
        return $objects;
    }

    //Find all records by a specific ID (FK)
    public static function findAllByID(mysqli $connection, $id, string $primary_key)
    {
        $table = static::$table;
        $stmt = $connection->prepare("SELECT * FROM $table WHERE $primary_key = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $objects = [];
        while ($row = $result->fetch_assoc()) {
            $objects[] = new static($row);
        }
        return $objects;
    }

    //Find record by PK
    public static function find(mysqli $connection, $id, string $primary_key = "id")
    {
        $table = static::$table;
        $stmt = $connection->prepare("SELECT * FROM $table WHERE $primary_key = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? new static($row) : null;
    }

    //Delete a record by PK
    public static function deleteByID(mysqli $connection, $id, string $primary_key = "id")
    {
        $table = static::$table;
        $stmt = $connection->prepare("DELETE FROM $table WHERE $primary_key = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    //Get parameter types for bind_param
    protected static function getAllTypes(array $data)
    {
        $types = '';
        foreach ($data as $value) {
            if (is_int($value)) $types .= 'i';
            elseif (is_float($value)) $types .= 'd';
            else $types .= 's';
        }
        return $types;
    }
}
?>