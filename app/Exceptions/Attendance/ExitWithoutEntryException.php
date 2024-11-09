<?php

namespace App\Exceptions\Attendance;

use Exception;

class ExitWithoutEntryException extends Exception
{
    protected $message = 'No hay una entrada registrada para hoy.';
}
