<?php
namespace App\Exception\Json;

use App\Exception\Core\PublishedMessageException;
use Exception;

class InvalidJsonPayloadException extends Exception implements PublishedMessageException{
protected $message = "You forgot to include required values in json payload!";
}