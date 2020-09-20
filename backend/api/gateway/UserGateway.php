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

    public function getAll()
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
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}
?>