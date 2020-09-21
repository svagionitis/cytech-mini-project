<?php

namespace Api\Gateway;

/**
 * A user gateway in order to
 * * get all users
 * * get a specific user
 * * add a user
 * * update a user
 * * delete a user
 */
class UserGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Get all users from DB
     */
    public function getUserAll()
    {
        $statement = "
            SELECT
                UserID, FirstName, LastName, Email, TravelDateStart, TravelDateEnd, TravelReason
            FROM
                user;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            echo "Error getUserAll: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Get user by a specific UserID
     */
    public function getUserByUserId($userId)
    {
        $statement = "
            SELECT
                UserID, FirstName, LastName, Email, TravelDateStart, TravelDateEnd, TravelReason
            FROM
                user
            WHERE
                UserID = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($userId));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            echo "Error getUserByUserId: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Get user by a specific Email
     */
    public function getUserByEmail($email)
    {
        $statement = "
            SELECT
                UserID, FirstName, LastName, Email, TravelDateStart, TravelDateEnd, TravelReason
            FROM
                user
            WHERE
                Email = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($email));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            echo "Error getUserByEmail: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Add a new user to db (SQL INSERT)
     */
    public function addUser(Array $input)
    {
        $statement = "
            INSERT INTO user
                (FirstName, LastName, Email, TravelDateStart, TravelDateEnd, TravelReason)
            VALUES
                (:FirstName, :LastName, :Email, :TravelDateStart, :TravelDateEnd, :TravelReason);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'FirstName' => $input['FirstName'],
                'LastName'  => $input['LastName'],
                'Email' => $input['Email'],
                'TravelDateStart' => $input['TravelDateStart'],
                'TravelDateEnd' => $input['TravelDateEnd'],
                'TravelReason' => $input['TravelReason'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            echo "Error addUser: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Update a user with a specific UserId (SQL UPDATE)
     */
    public function updateUserByUserId($userId, Array $input)
    {
        $statement = "
            UPDATE user
            SET
                FirstName = :FirstName,
                LastName  = :LastName,
                Email = :Email,
                TravelDateStart = :TravelDateStart,
                TravelDateEnd = :TravelDateEnd,
                TravelReason = :TravelReason
            WHERE
                UserId = :UserId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'UserId' => (int) $userId,
                'FirstName' => $input['FirstName'],
                'LastName'  => $input['LastName'],
                'Email' => $input['Email'],
                'TravelDateStart' => $input['TravelDateStart'],
                'TravelDateEnd' => $input['TravelDateEnd'],
                'TravelReason' => $input['TravelReason'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            echo "Error updateUserByUserId: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Delete a user with a specific UserId (SQL DELETE)
     */
    public function deleteUserByUserId($userId)
    {
        $statement = "
            DELETE FROM user
            WHERE UserId = :UserId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('UserId' => $userId));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            echo "Error deleteUserByUserId: " . $e->getMessage();
            exit();
        }
    }

}
?>