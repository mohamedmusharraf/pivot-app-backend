<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class GroupChallengeException extends Exception
{
    public function __construct(string $message, protected int $status = 422)
    {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->status);
    }
}
