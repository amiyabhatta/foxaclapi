<?php

namespace Fox\common;

class Base {

    protected $statusCode = 200;

    /**
     * Get Status Code
     * 
     * @return type
     */
    function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set Status Code
     *
     * @param type $statusCode
     * @return $this
     */
    function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Return Type For Respond Not Found
     *
     * @param type $message
     * @return type
     */
    public function respondNotFound($message = 'Not Found!')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * Return Type For Respond Internal Error
     * 
     * @param type $message
     * @return type
     */
    public function respondInternalError($message = 'Internal Server Error!')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    /**
     * Return Type For All Success Response
     *
     * @param type $data
     * @param type $headers
     * @return type
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Return Type For Respond With Token
     * 
     * @param type $token
     * @return type
     */
    public function respondWithToken($token)
    {
        return $this->respond(['data' => $token, 'status_code' => 201]);
    }

    /**
     * Return Type For Respond With Error
     * 
     * @param type $message
     * @return type
     */
    public function respondWithError($message) {
        return $this->respond(['error' => [
                        'message' => $message,
                        'status_code' => $this->getStatusCode()
        ]]);
    }

}
