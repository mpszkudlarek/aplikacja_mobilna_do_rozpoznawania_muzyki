<?php


namespace App\Exception\Entity;

use App\Exception\Core\PublishedMessageException;
use Exception;

class EntityNotFoundException extends Exception implements PublishedMessageException{}