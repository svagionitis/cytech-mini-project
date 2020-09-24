<?php
namespace Api\Controller;

require 'api/config/DatabaseConnector.php';
require 'api/gateway/UserGateway.php';

use Api\Config\DatabaseConnector;
use Api\Gateway\UserGateway;

/**
 *
 */
class UserController {

    private $db;
    private $requestMethod;
    private $userId;
    private $email;
    private $page;
    private $numberOfPages;
    private $limit;
    private $sort_by;
    private $order_by;

    private $userGateway;

    public function __construct($userId, $email)
    {
        $this->userId = $userId;
        $this->email = $email;
    }

    public function processRequest()
    {
        $this->preProcessRequest();

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->userId) {
                    $response = $this->getUserByUserId($this->userId);
                }
                else if ($this->email) {
                    $response = $this->getUserByEmail($this->email);
                }
                else {
                    if (isset($this->page) && isset($this->limit)) {
                        $response = $this->getUserAllPagination();
                    } else if (isset($this->sort_by) && isset($this->order_by)) {
                        $response = $this->getUserAllSorting();
                    } else {
                        $response = $this->getUserAll();
                    }
                }
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

    private function preProcessRequest()
    {
        $this->db = (new DatabaseConnector())->getConnection();
        $this->userGateway = new UserGateway($this->db);

        $this->requestMethod = $_SERVER["REQUEST_METHOD"];

        // Pagination
        // TODO Create separate function
        if (isset($_GET['page']) && $_GET['page'] != ""
            && isset($_GET['limit']) && $_GET['limit'] != "") {

            $page = $_GET['page'];
            if ($page <= 0) {
                $page = 1;
            }
            $this->page = $page;

            $this->limit = $_GET['limit'];

            $numberOfPages = (int) ceil($this->userGateway->getUserAllTotalRows() / $this->limit);
            $this->numberOfPages = $numberOfPages;

            // If the page number is more than the total number of pages,
            // then return not found error.
            if ($this->page > $this->numberOfPages) {
                return $this->notFoundResponse();
            }
        }

        // Sorting
        // TODO Create separate funcion
        if (isset($_GET['sort_by']) && $_GET['sort_by'] != ""
            && isset($_GET['order_by']) && $_GET['order_by'] != "") {

            try {
                $this->sort_by = $this->white_list($_GET['sort_by'],
                                                    ["FirstName", "LastName", "Email",
                                                    "TravelDateStart", "TravelDateEnd", "TravelReason"],
                                                    "Invalid sortby field name");
                $this->order_by = $this->white_list($_GET['order_by'],
                                                    ["ASC","DESC"],
                                                    "Invalid orderby direction");
            } catch (\InvalidArgumentException $e) {
                return $this->notFoundResponseWithResponse($e->getMessage());
                exit();
            }
        }

    }

    private function getUserAll()
    {
        $result = $this->userGateway->getUserAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getUserAllPagination()
    {
        $offset = ($this->page - 1) * $this->limit;
        $result = $this->userGateway->getUserAllLimit($offset, $this->limit);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getUserAllSorting()
    {
        $result = $this->userGateway->getUserAllSorting($this->sort_by, $this->order_by);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getUserByUserId($userId)
    {
        $result = $this->userGateway->getUserByUserId($userId);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getUserByEmail($email)
    {
        $result = $this->userGateway->getUserByEmail($email);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

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

    private function updateUserByUserId($userId)
    {
        $result = $this->userGateway->getUserByUserId($userId);
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

    private function deleteUserByUserId($userId)
    {
        $result = $this->userGateway->getUserByUserId($userId);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->userGateway->deleteUserByUserId($userId);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateUser($input)
    {
        if (! isset($input['FirstName'])) {
            return false;
        }
        if (! isset($input['LastName'])) {
            return false;
        }
        if (! isset($input['Email'])) {
            return false;
        }
        if (! isset($input['TravelDateStart'])) {
            return false;
        }
        if (! isset($input['TravelDateEnd'])) {
            return false;
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

    private function notFoundResponseWithResponse($message)
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode([
            'error' => $message
        ]);
        return $response;
    }

    /**
     * See https://phpdelusions.net/pdo_examples/order_by
     */
    private function white_list(&$value, $allowed, $message) {
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
}
?>