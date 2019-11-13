<?php

namespace App\Service;

class Pagination {

    public function isValidParameters($page, $limit): bool
    {
        if($this->isDefined($page, $limit))
        {
            if($this->isValidPage($page) && $this->isValidLimit($limit))
            {
                return true;
            }
            return false;
        }
        return true;
    }

    public function isDefined($page, $limit): bool
    {
        if(!is_null($page) || !is_null($limit)) { return true; }
        return false;
    }

    public function isValidPage($page): bool
    {
        if(is_numeric($page) && $page > 0) { return true; }
        return false;
    }

    public function isValidLimit($limit): bool
    {
        if(is_numeric($limit) && $limit > 0) { return true; }
        return false;
    }
}