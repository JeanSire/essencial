<?php

namespace Controllers;

/**
 * Class Controller404
 * @package Controllers
 */
class Controller404
{
    /** Controller404 constructor. */
    public function __construct()
    {
        echo '{"status": false, "data": "Api is not recognized!"}';
    } // _construct
} // Controller404