<?php

namespace App\Exception\Entity;

use App\Exception\Core\PublishedMessageException;
use Exception;

class EntityValidationsException extends  Exception implements  PublishedMessageException{}