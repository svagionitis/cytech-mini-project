<?php
namespace Api\Controller;

require 'api/gateway/UserGateway.php';

use Api\Gateway\UserGateway;

/**
 *
 */
class UserController {

    private $db;
    private $requestMethod;
    private $userId;
    private $email;

    private $userGateway;

    public function __construct($db, $requestMethod, $userId, $email)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        $this->email = $email;

        $this->userGateway = new UserGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->userId) {
                    $response = $this->getUserByUserId($this->userId);
                }
                else if ($this->email) {
                    $response = $this->getUserByEmail($this->email);
                }
                else {
                    $response = $this->getUserAll();
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

    private function getUserAll()
    {
        $result = $this->userGateway->getUserAll();
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
}
?>