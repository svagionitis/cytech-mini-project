<?php

namespace Api\Gateway;

/**
 * The main class communicating with the db and sending SQL queries
 *
 * TODO Need proper error handling.
 */
class UserGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Get the total rows for the user table
     *
     * TODO This will be slow in large dbs. Maybe try something else to improve it.
     */
    public function getUserAllTotalRows()
    {
        $statement = "SELECT COUNT(*) AS TOTAL_COUNT FROM user";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result[0]["TOTAL_COUNT"];
        } catch (\PDOException $e) {
            echo "Error getUserAllTotalRows: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Get the total rows for the user table which are filtered
     *
     * TODO This will be slow in large dbs. Maybe try something else to improve it.
     */
    public function getUserAllTotalRowsFiltered($filter_by, $filter_by_value)
    {
        $statement = "SELECT COUNT(*) AS TOTAL_COUNT_FILTER FROM user";

        // If only the $filter_by_value is set, then search all the columns.
        // If both $filter_by and $filter_by_value are set, then search only in
        // the $filter_by column.
        if (isset($filter_by_value) && !empty($filter_by_value)) {
            if (isset($filter_by) && !empty($filter_by)) {
                // Search in a specific column with regex
                $statement .= " WHERE $filter_by REGEXP '$filter_by_value'";
            } else {
                // Search in all columns with exact match
                // TODO Need a better way to get the column names
                $allColumns = "(UserID, FirstName, LastName, Email, TravelDateStart, TravelDateEnd, TravelReason)";
                $statement .= " WHERE '$filter_by_value' IN $allColumns";
            }
        }

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result[0]["TOTAL_COUNT_FILTER"];
        } catch (\PDOException $e) {
            echo "Error getUserAllTotalRows: " . $e->getMessage();
            exit();
        }
    }

    /**
     * This function get the users from db and also performs some basic filtering, sorting and paging
     *
     * @param $filter_by The field name to filter the data
     * @param $filter_by_value The value of the field name to filter the data
     * @param $sort_by The field name to sort the data
     * @param $order_by The direction to sort the data ascending or descending
     * @param $offset The number of rows to skip
     * @param $limit The total number of rows to retrieve
     *
     * TODO Needs a proper query builder to do any checks and everything
     */
    public function getUserAllLimitSortFilter($filter_by, $filter_by_value, $sort_by, $order_by, $offset, $limit)
    {
        $statement = "SELECT * FROM user";

        // If only the $filter_by_value is set, then search all the columns.
        // If both $filter_by and $filter_by_value are set, then search only in
        // the $filter_by column.
        if (isset($filter_by_value) && !empty($filter_by_value)) {
            if (isset($filter_by) && !empty($filter_by)) {
                // Search in a specific column with regex
                $statement .= " WHERE $filter_by REGEXP '$filter_by_value'";
            } else {
                // Search in all columns with exact match
                // TODO Need a better way to get the column names
                $allColumns = "(UserID, FirstName, LastName, Email, TravelDateStart, TravelDateEnd, TravelReason)";
                $statement .= " WHERE '$filter_by_value' IN $allColumns";
            }
        }

        if (isset($sort_by) && !empty($sort_by)) {
            $statement .= " ORDER BY $sort_by";

            // order type (ASC, DESC) needs ORDER BY
            if (isset($order_by) && !empty($order_by)) {
                $statement .= " $order_by";
            }
        }

        if (isset($limit) && !empty($limit)) {
            $statement .= " LIMIT $limit";
        }

        // OFFSET needs LIMIT
        if (isset($offset) && !empty($offset)) {
            $statement .= " OFFSET $offset";
        }

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            echo "Error getUserAllLimitSort: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Add a new user to db (SQL INSERT)
     *
     * @param $input An array with the values to be added in db
     */
    public function addUser(Array $input)
    {
        $statement = "
            INSERT INTO user
                (FirstName, LastName, Email, TravelDateStart, TravelDateEnd, TravelReason)
            VALUES
                (:FirstName, :LastName, :Email, :TravelDateStart, :TravelDateEnd, :TravelReason)
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':FirstName' => $input['FirstName'],
                ':LastName'  => $input['LastName'],
                ':Email' => $input['Email'],
                ':TravelDateStart' => $input['TravelDateStart'],
                ':TravelDateEnd' => $input['TravelDateEnd'],
                ':TravelReason' => $input['TravelReason'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            echo "Error addUser: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Update a user with a specific UserId (SQL UPDATE)
     *
     * @param $userId The UserId of the user to update
     * @param $input An array with the values to be updated
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
                UserId = :UserId
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':UserId' => (int) $userId,
                ':FirstName' => $input['FirstName'],
                ':LastName'  => $input['LastName'],
                ':Email' => $input['Email'],
                ':TravelDateStart' => $input['TravelDateStart'],
                ':TravelDateEnd' => $input['TravelDateEnd'],
                ':TravelReason' => $input['TravelReason'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            echo "Error updateUserByUserId: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Delete a user with a specific UserId (SQL DELETE)
     *
     * @param $userId The UserId of the user to delete
     */
    public function deleteUserByUserId($userId)
    {
        $statement = "
            DELETE FROM user
            WHERE UserId = :UserId
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(':UserId' => $userId));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            echo "Error deleteUserByUserId: " . $e->getMessage();
            exit();
        }
    }

}
?>