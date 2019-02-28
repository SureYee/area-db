<?php

namespace Sureyee\AreaDB;


interface Driver
{
    public function get(int $code);
}