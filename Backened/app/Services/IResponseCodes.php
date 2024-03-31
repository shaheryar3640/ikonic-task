<?php

namespace App\Services;

interface IResponseCodes
{
    // Api response codes
    const BAD_REQUEST           = 400;
    const UNAUTHENTICATED       = 401;
    const SUCCESS               = 200;
    const REDIRECT              = 302;
    const INTERNAL_SERVER_ERROR = 500;
    const NOT_FOUND             = 404;
    const Validator_error       = 403;
}
