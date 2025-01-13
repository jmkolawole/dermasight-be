<?php

namespace App\Traits;

trait SendsApiResponse
{
    private $allowedStatusCodes = [200, 201, 400, 401, 403, 404, 500];

    protected $data = [];

    /**
     * Validate status code
     * 
     * @param int $statusCode
     * @return int
     */
    private function isStatus($statusCode) {
        return in_array($statusCode, $this->allowedStatusCodes) ? $statusCode : 500;
    }

    /**
     * Structure and send failure response
     * 
     * @param string|array|object $error
     * @param int $statusCode
     */
    public function failure($error = 'Something went wrong, please try again', $statusCode = 500) {
        if (gettype($error) == 'object') {
            $exceptionClass = get_class($error);

            if ($exceptionClass == 'Illuminate\Database\Eloquent\ModelNotFoundException') {
                throw new $exceptionClass($error);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'Something went wrong, please try again'
                ], 500);
            }
        } else {
            return response()->json([
                'status' => false,
                'error' => $error
            ], $this->isStatus($statusCode));
        }
    }

    /**
     * Attach data to success response
     * 
     * @param mixed $data
     */
    public function with($data) {
        $this->data = $data;

        return $this;
    }

    /**
     * Structure and send success response
     * 
     * @param string|array $error
     * @param int $statusCode
     */
    public function success($statusCode = 200, $message = "") {
        $successArray = [
            'status' => true
        ];

        // Attach message if present
        if (strlen($message) > 0) {
            $successArray['message'] = $message;
        }

        // Attach data if present
        if ($this->data || count($this->data) > 0) {
            $successArray['data'] = $this->data;
        }

        $this->data = [];

        return response()->json($successArray, $this->isStatus($statusCode));
    }
}
