<?php

namespace SafeMailer;

use Illuminate\Support\Facades\Facade;

class SafeMailerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'safemailer';
    }
} 