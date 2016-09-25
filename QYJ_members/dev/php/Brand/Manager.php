<?php
require_once('Factory/BrandFactory.php');

/**
 * Manager for brand factory
 */
class Manager
{
    
    public function setBrand($brand)
    {
        return BrandFactory::getInstance($brand);
    }
}
