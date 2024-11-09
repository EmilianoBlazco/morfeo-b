<?php

namespace App\Exceptions\Attendance;

use Exception;

class EntryAlreadyExistsException extends Exception
{
    protected $message = 'Ya has registrado una entrada hoy.';
}
