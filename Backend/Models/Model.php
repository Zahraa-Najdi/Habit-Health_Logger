<?php
abstract class Model
{
    public function __construct($data)
    {
    }

    protected static string $table;
    // protected static string $primary_key = "id";

    //CREATE
    public static function create(mysqli $connection, array $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = str_repeat("?,", count($data) - 1) . "?";

        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", static::$table, $columns, $placeholders);
        $query = $connection->prepare($sql);
        $types = self::getAllTypes($data);
        $params = [];
        foreach ($data as $key => &$value) {
            $params[] =& $value;
        }

        call_user_func_array(array($query, "bind_param"), array_merge(array($types), $params));
        $query->execute();
        if ($connection->errno == 1062)
            return "Duplicate";
        return $connection->insert_id;

    }

    //UPDATE
    public static function update(mysqli $connection, int $id, array $data,$primary_key)
    {
        $updates = "";
        $i = 0;
        foreach ($data as $key => &$value) {
            $updates .= ($i === count($data) - 1) ? "" . $key . "= ?" : "" . $key . "= ?,";
            $i++;
        }
        $sql = sprintf("UPDATE %s SET %s WHERE %s = ?", static::$table, $updates, $primary_key);
        $query = $connection->prepare($sql);
        $types = self::getAllTypes($data);
        $types .= "i";
        $params = [];
        foreach ($data as $key => &$value) {
            $params[] = &$value;
        }
        $params[] = &$id;
        call_user_func_array(array($query, "bind_param"), array_merge(array($types), $params));
        $query->execute();
        if($connection->errno ==1062){
            return "Duplicate";
        }
        return $id;
    }

    //GET_ALL
    public static function findAll(mysqli $connection)
    {
        $sql = sprintf("SELECT * FROM %s", static::$table);

        $query = $connection->prepare($sql);
        $query->execute();

        $result = $query->get_result();
        $objects = [];

        while ($row = $result->fetch_assoc()) {
            $objects[] = new static($row);
        }

        return $objects;
    }
    public static function findAllById(mysqli $connection,$id,$primary_key)
    {
        $sql = sprintf("SELECT * FROM %s WHERE %s = ? ", static::$table,$primary_key);

        $query = $connection->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();

        $result = $query->get_result();
        $objects = [];

        while ($row = $result->fetch_assoc()) {
            $objects[] = new static($row);
        }

        return $objects;
    }

    //GET_BY_ID
    public static function find(mysqli $connection, int $id,$primary_key)
    {
        $sql = sprintf(
            "SELECT * from %s WHERE %s = ?",
            static::$table,
            $primary_key
        );

        $query = $connection->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();

        $data = $query->get_result()->fetch_assoc();

        return $data ? new static($data) : null;
    }

    //DELETE_BY_ID
    public static function deleteById($id, mysqli $connection,$primary_key)
    {
        $sql = sprintf("DELETE FROM %s WHERE %s = ?", static::$table, $primary_key);
        $query = $connection->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();
        return true;
    }


    public static function getAllTypes($data)
    {
        $types = "";
        foreach ($data as $key => $value) {
            if (gettype($value) == "string") {
                $types .= "s";
            } elseif (gettype($value) == "integer") {
                $types .= "i";
            } elseif (gettype($value) == "float" || gettype($value) == "double") {
                $types .= "d";
            }
        }

        return $types;
    }



    //use past tense for commits
    //add backend -> or frontend -> 

}


?>