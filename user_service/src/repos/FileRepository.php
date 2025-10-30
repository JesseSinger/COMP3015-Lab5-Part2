<?php

namespace NewCo\UserService\repos;

require_once 'Repository.php';

require_once __DIR__ . '/../models/photo.php';

use Photo;

class FileRepository extends Repository
{
    /**
     * Fetch all posts from the database.
     * @return Post[]
     */
    public function getAllPhotos(): array
    {
        $sqlStatement = $this->pdo->query("SELECT * FROM photos;");
        $rows = $sqlStatement->fetchAll();

        $photos = [];
        foreach ($rows as $row) {
            $photos[] = (new Photo())->fill($row);
        }

        return $photos;
    }


    /**
     * Save a new Photo to the database.
     * On success, the File object will be updated with the id assigned by the database.
     */
    public function save(Photo $photo): void
    {
        $uploadedAt = date('Y-m-d H:i:s');

        // 1. query to execute against the DB

        try {
            $sqlStatement = $this->pdo->prepare("INSERT INTO photos (file_name, metadata, uploaded_at) VALUES (?, ?, ?);");
            $result = $sqlStatement->execute([$photo->getFilename(), $photo->getMetadata(), $uploadedAt]);
            if ($result) {
                // 2. get the last ID inserted, on a per connection basis
                $id = $this->pdo->lastInsertId();
                // 3. issue a select query to the DB
                $sqlStatement = $this->pdo->prepare("SELECT * FROM photos where id = ?");
                $sqlStatement->execute([$id]);
                $result = $sqlStatement->fetch();

                // 4. fetch the result as an associative array, and turn it into an object.
                if ($result) {
                    $photo->fill($result);
                }
            }
        } catch (\PDOException $e) {
            //Log::error($e->getMessage());
        }
    }

    // /**
    //  * Find a Post by its ID.
    //  * @return Post|null the Post if found, null otherwise
    //  */
    // public function findById(int $id): ?Post
    // {
    //     try {
    //         $sqlStatement = $this->pdo->prepare('SELECT * FROM posts WHERE id=?');
    //         $result = $sqlStatement->execute([$id]);
    //         if ($result) {
    //             $resultSet = $sqlStatement->fetch();

    //             if ($resultSet) {
    //                 return (new Post())->fill($resultSet);
    //             }
    //         }
    //     } catch (\PDOException $e) {
    //         Log::error($e->getMessage());
    //     }

    //     return null;
    // }

    // /**
    //  * Update an existing Post in the database.
    //  * @return bool true on success, false otherwise
    //  */
    // public function update(Post $post): bool
    // {
    //     try {
    //         $sqlStatement = $this->pdo->prepare("UPDATE posts SET updated_at = ?, body = ?, title = ? WHERE id = ?;");
    //         $result = $sqlStatement->execute([date('Y-m-d H:i:s'), $post->getBody(), $post->getTitle(), $post->getId()]);
    //         if ($result) {
    //             return true;
    //         }
    //     } catch (\PDOException $e) {
    //         Log::error($e->getMessage());
    //     }

    //     return false;
    // }

    // /**
    //  * @return bool true on success, false otherwise
    //  */
    // public function delete(Post $post): bool
    // {
    //     try {
    //         $sqlStatement = $this->pdo->prepare("DELETE FROM posts WHERE id = ?;");
    //         $result = $sqlStatement->execute([$post->getId()]);
    //         if ($result) {
    //             return true;
    //         }
    //     } catch (\PDOException $e) {
    //         Log::error($e->getMessage());
    //     }

    //     return false;
    // }
}
