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
        if(isset($page) || isset($limit)) { return true; }
        return false;
    }

    public function isValidPage($page): bool
    {
        if (is_numeric($page))
        {
            $page = (int)$page;
            if($page > 0) { return true; }
        }
        return false;
    }

    public function isValidLimit($limit): bool
    {
        if (is_numeric($limit))
        {
            $limit = (int)$limit;
            if($limit > 0) { return true; }
        }
        return false;
    }
}