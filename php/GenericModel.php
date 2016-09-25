<?php
require_once("../variables.php");

/**
 * Base model class GenericModel
 *
 */
class GenericModel
{
    public function __construct() {
        session_start();
    }
}
