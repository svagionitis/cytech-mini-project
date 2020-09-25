<?php
namespace Api\Controller;

require 'api/config/DatabaseConnector.php';
require 'api/gateway/UserGateway.php';

use Api\Config\DatabaseConnector;
use Api\Gateway\UserGateway;

/**
 * The main class creating the REST API for the user
 *
 * TODO Need proper error handling.
 */
class UserController {

    private $db;
    private $requestMethod;
    private $userId;
    private $page;
    private $numberOfPages;
    private $limit;
    private $sort_by;
    private $order_by;
    private $columnNames;
    private $filter_by;
    private $filter_by_value;
    private $generate_users;

    private $userGateway;

    public function __construct($userId)
    {
        $this->userId = $userId;

        // TODO It must be a better way to do this, but this is ok for now
        $this->columnNames = ["UserId", "FirstName", "LastName", "Email",
                              "TravelDateStart", "TravelDateEnd", "TravelReason"];
    }

    /**
     * Process the request
     */
    public function processRequest()
    {
        $response = $this->preProcessRequest();
        if (isset($response)) {
            header($response['status_code_header']);
            if ($response['body']) {
                echo $response['body'];
            }
        } else {
            switch ($this->requestMethod) {
                case 'GET':
                    if (isset($this->generate_users)) {
                        $this->generateUsers();
                        exit();
                    }
                    $response = $this->getUserAllPaginationSortingFiltering();
                    break;
                case 'POST':
                    $response = $this->addUser();
                    break;
                case 'PUT':
                    $response = $this->updateUserByUserId($this->userId);
                    break;
                case 'DELETE':
                    $response = $this->deleteUserByUserId($this->userId);
                    break;
                default:
                    $response = $this->notFoundResponse();
                    break;
            }
            header($response['status_code_header']);
            if ($response['body']) {
                echo $response['body'];
            }
        }
    }

    /**
     * Initialize some variables which will be used later on in processRequest()
     * Also check the input variables for paging, sorting and filtering
     */
    private function preProcessRequest()
    {
        $this->db = (new DatabaseConnector())->getConnection();
        $this->userGateway = new UserGateway($this->db);

        $this->requestMethod = $_SERVER["REQUEST_METHOD"];

        $response = $this->preProcessPaging();
        if (isset($response)) {
            return $response;
        }

        $response = $this->preProcessSorting();
        if (isset($response)) {
            return $response;
        }

        $response = $this->preProcessFiltering();
        if (isset($response)) {
            return $response;
        }

        $response = $this->preProcessGenerateUsers();
        if (isset($response)) {
            return $response;
        }
    }

    /**
     * If the page and limit are not set, the default values are
     * page=1 and limit=0.
     */
    private function preProcessPaging()
    {
        $this->page = 1;
        $this->limit = 0;
        if (isset($_GET['page']) && $_GET['page'] != ""
            && isset($_GET['limit']) && $_GET['limit'] != "") {

            if (!is_numeric($_GET['page'])) {
                return $this->notFoundResponseWithMessage("Page value is not numeric.");
            }

            $this->page = $_GET['page'];
            if ($this->page <= 0) {
                return $this->notFoundResponseWithMessage("Page value is zero or negative.");
            }

            if (!is_numeric($_GET['limit'])) {
                return $this->notFoundResponseWithMessage("Limit value is not numeric.");
            }

            $this->limit = $_GET['limit'];
            if ($this->limit <= 0) {
                return $this->notFoundResponseWithMessage("Limit value is zero or negative.");
            }

            $this->numberOfPages = (int) ceil($this->userGateway->getUserAllTotalRows() / $this->limit);
            // If the page number is more than the total number of pages,
            // then return not found error.
            if ($this->page > $this->numberOfPages) {
                return $this->notFoundResponseWithMessage("The pag3 $this->page is beyond the total number of pages $this->numberOfPages.");
            }
        }
    }

    /**
     * If the sort_by and order_by are not set, the default values are
     * sort_by=UserId and order_by=ASC
     */
    private function preProcessSorting()
    {
        try {
            $this->sort_by = $this->allowedValues($_GET['sort_by'], $this->columnNames,
                                                  "Invalid sort_by value");

            $this->order_by = $this->allowedValues($_GET['order_by'],
                                                    ["ASC","DESC"],
                                                    "Invalid order_by value");
        } catch (\InvalidArgumentException $e) {
            return $this->notFoundResponseWithMessage($e->getMessage());
        }
    }

    /**
     * You can request only one value for filtering and with exact match
     * TODO Get more than the first value
     * TODO You can get only exact matches, eg filter_by=filter_by_value but you can not do
     * filtering wit a range like filter_by>filter_by_value or filter_by<=filter_by_value
     */
    private function preProcessFiltering()
    {
        $filter_by_array = $this->getArrayKeysExist($_GET, $this->columnNames);
        if (!empty($filter_by_array)) {
            $this->filter_by = $filter_by_array[0];

            if (isset($_GET[$this->filter_by]) && $_GET[$this->filter_by] != "") {
                $this->filter_by_value = $_GET[$this->filter_by];
            } else {
                return $this->notFoundResponseWithMessage("No value for $this->filter_by");
            }
        }
    }

    private function preProcessGenerateUsers()
    {
        if (isset($_GET['generate_users']) && $_GET['generate_users'] != "") {
            if (!is_numeric($_GET['generate_users'])) {
                return $this->notFoundResponseWithMessage("generate_users value is not numeric.");
            }

            $this->generate_users = $_GET['generate_users'];
        }
    }

    private function generateUsers()
    {
        // Error: Maximum execution time of 120 seconds exceeded in
        // https://stackoverflow.com/questions/37123111/maximum-execution-time-of-120-seconds-exceeded-in-yii2
        set_time_limit(500);

        $letters="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        for ($i = 0; $i < $this->generate_users; $i++) {
            // https://code.tutsplus.com/tutorials/generate-random-alphanumeric-strings-in-php--cms-32132
            $user['FirstName'] = substr(str_shuffle($letters), 0, mt_rand(5, 30));
            $user['LastName'] = substr(str_shuffle($letters), 0, mt_rand(7, 50));
            $user['Email'] = $user['FirstName'] . '.' . $user['LastName'] . '@' . substr(str_shuffle($letters), 0, mt_rand(2, 50)) . '.com';
            // https://thisinterestsme.com/generate-random-date-php/
            $user['TravelDateStart'] = date("Y-m-d", mt_rand(1, time()));
            $user['TravelDateEnd'] = date("Y-m-d", mt_rand(1, time()));
            $user['TravelReason'] = $this->genRandomString(mt_rand(1024, 10240));

            print_r($i);
            print_r($user);

            $this->userGateway->addUser($user);
        }
    }

    /**
     * See https://www.geeksforgeeks.org/generating-random-string-using-php/
     */
    private function genRandomString($n)
    {
        $characters = ' 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!\"£$%^&*(){}[]@~#?/<>_-+=';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    /**
     * Given an array of keys $keys, get the common occurences to an input array $input.
     *
     * @param $input The array which will check the keys
     * @param $keys An array of keys to check
     */
    private function getArrayKeysExist(array $input, array $keys)
    {
        return array_values(array_intersect(array_keys($input), $keys));
    }

    /**
     * This is a GET request to get the users from db with pagination,
     * sorting and filtering
     */
    private function getUserAllPaginationSortingFiltering()
    {
        // This offset is needed by the SQL query
        $offset = ($this->page - 1) * $this->limit;
        $result = $this->userGateway->getUserAllLimitSortFilter($this->filter_by, $this->filter_by_value,
                                                                $this->sort_by, $this->order_by,
                                                                $offset, $this->limit);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    /**
     * This is a POST request to add a user in db
     */
    private function addUser()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateUser($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->userGateway->addUser($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    /**
     * This is a PUT request to update a user in db
     *
     * @param $userId The UserId of the user to update
     */
    private function updateUserByUserId($userId)
    {
        $result = $this->userGateway->getUserAllLimitSortFilter('UserId', $userId, null, null, 1, 0);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateUser($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->userGateway->updateUserByUserId($userId, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    /**
     * This is a DELETE request to delete a user in db
     *
     * @param $userId The UserId of the user to delete

     */
    private function deleteUserByUserId($userId)
    {
        $result = $this->userGateway->getUserAllLimitSortFilter('UserId', $userId, null, null, 1, 0);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->userGateway->deleteUserByUserId($userId);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    /**
     * TODO Need to properly validate the values
     * See https://www.w3schools.com/php/php_form_url_email.asp
     */
    private function validateUser($input)
    {
        if (! isset($input['FirstName'])) {
            return false;
        } else {
            if (!preg_match("/^[a-zA-Z-' ]*$/",$input['FirstName'])) {
                return false;
            }
        }

        if (! isset($input['LastName'])) {
            return false;
        } else {
            if (!preg_match("/^[a-zA-Z-' ]*$/",$input['LastName'])) {
                return false;
            }
        }

        if (! isset($input['Email'])) {
            return false;
        } else {
            if (!filter_var($input['Email'], FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }

        if (! isset($input['TravelDateStart'])) {
            return false;
        } else {
            if (!$this->checkDateFormat($input['TravelDateStart'])){
                return false;
            }
        }

        if (! isset($input['TravelDateEnd'])) {
            return false;
        } else {
            if (!$this->checkDateFormat($input['TravelDateEnd'])){
                return false;
            }
        }

        if (! isset($input['TravelReason'])) {
            return false;
        }

        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode([
            'error' => 'Not Found'
        ]);
        return $response;
    }

    private function notFoundResponseWithMessage($message)
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode([
            'error' => $message
        ]);
        return $response;
    }

    /**
     * Check if the $value is included in the $allowed values.
     * If the value is not set, get the first value from the $allowed array.
     * If the value is not in the $allowed array, then throw an exception.
     *
     * @param $value Value to check if it is allowed
     * @param $allowed A list of allowed values to check against
     * @param $message An error message
     *
     * See https://phpdelusions.net/pdo_examples/order_by
     */
    private function allowedValues(&$value, $allowed, $message) {
        if ($value === null) {
            return $allowed[0];
        }
        $key = array_search($value, $allowed, true);
        if ($key === false) {
            throw new \InvalidArgumentException($message);
        } else {
            return $value;
        }
    }

    /**
     * Check for the format of date YYYY-MM-DD
     *
     * @param $date A date in format YYYY-MM-DD
     *
     * See https://gist.github.com/voku/dd277e9c660f38b8c3a3
     */
    private function checkDateFormat($date)
    {
        // match the format of the date
        if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
        {
            // check whether the date is valid or not
            if (checkdate($parts[2], $parts[3], $parts[1])) {
                return true;
            }
        }

        return false;
    }
}
?>