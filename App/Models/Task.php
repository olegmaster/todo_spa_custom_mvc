<?php

namespace App\Models;

use Core\Model;
use PDO;

/**
 * Reviews
 *
 */
class Task extends Model
{
    const PAGE_ITEMS_COUNT = 3;

    /**
     * Get all the tasks as an associative array
     *
     * @param int $page
     * @param string $orderBy
     * @param string $orderDirection
     * @return array
     */
    public static function getTasks(int $page, string $orderBy = 'id', string $orderDirection = 'DESC')
    {

        $db = static::getDB();
        $recordsCount = self::getRecordsCount();

        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $totalPages = (int)ceil($recordsCount / self::PAGE_ITEMS_COUNT);

        $links = [
            "first" => $actual_link . "/?get_tasks&page=1",
            "last" => $actual_link . "/?get_tasks&page=" . $totalPages,
            "prev" => $page === 1 ? null : "/?get_tasks&page=" . ($page - 1 ),
            "next" => $page === $totalPages ? null : $actual_link . "/?get_tasks&page=" . ($page + 1)
        ];

        $meta = [
            'current_page' => $page,
            'last_page' => $totalPages,
            'per_page' => self::PAGE_ITEMS_COUNT,
        ];

        $order = in_array($orderBy, ['id', 'username', 'email', 'text', 'is_completed']) ? $orderBy : 'id';
        $direction = in_array($orderDirection, ['DESC', 'ASC']) ? $orderDirection : 'DESC';
        $stmt = $db->query("SELECT * FROM tasks ORDER BY $order $direction LIMIT " . self::PAGE_ITEMS_COUNT . ' OFFSET ' . (($page - 1) * self::PAGE_ITEMS_COUNT));
        $data = (array)$stmt->fetchAll(PDO::FETCH_ASSOC);


        $result["data"] = $data;
        $result["links"] = $links;
        $result["meta"] = $meta;

        return $result;
    }


    public static function createTask($data)
    {
        $db = static::getDB();
        $sql = "INSERT INTO tasks (username, email, text)
VALUES (:username, :email, :text)";

        try {
            $statement = $db->prepare($sql);
            $statement->bindValue(":username", $data['username']);
            $statement->bindValue(":email", $data['email']);
            $statement->bindValue(":text", $data['text']);
            $statement->execute();
        } catch (\PDOException $e) {
            return false;
        }

        return true;
    }

    public static function updateTask($data)
    {
        $db = static::getDB();
        $sql = "UPDATE tasks SET username=:username, email=:email, text=:text, is_completed=:is_completed where id=:id";

        try {
            $statement = $db->prepare($sql);
            $statement->bindValue(":id", $data['id']);
            $statement->bindValue(":username", $data['username']);
            $statement->bindValue(":email", $data['email']);
            $statement->bindValue(":text", $data['text']);
            $statement->bindValue(":is_completed", $data['is_completed']);
            $statement->execute();
        } catch (\PDOException $e) {
            return false;
        }

        return true;
    }

    private static function getRecordsCount()
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM tasks');
        $stmt->execute();
        return $stmt->rowCount();

    }
}
