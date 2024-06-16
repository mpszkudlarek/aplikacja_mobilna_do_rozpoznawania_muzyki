<?php

namespace App\Exception\Recognition;

use App\Exception\Core\PublishedMessageException;
use Exception;
class NoRequiredFileException extends Exception implements PublishedMessageException{
    protected $message = "You forgot to include mp3 file in the request payload or the file is corrupted";
}