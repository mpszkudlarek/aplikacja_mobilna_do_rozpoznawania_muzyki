<?php

namespace App\Exception\Json;

use App\Exception\Core\PublishedMessageException;
use Exception;

class InvalidJsonStructureException extends Exception implements PublishedMessageException{
    protected $message = 'Invalid json structure! You probably made a typo';
}