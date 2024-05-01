<?php

namespace App\Exception\User\Register;

use App\Exception\Core\PublishedMessageException;
use Exception;

class UserAlreadyExistsException extends Exception implements PublishedMessageException{}