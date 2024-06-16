<?php

namespace App\Exception\User;

use App\Exception\Core\PublishedMessageException;
use Exception;

class CannotGetUserFromTokenException extends Exception implements PublishedMessageException{
    protected $message = "Cannot get user data from token. The token is either invalid or expired";
}