<?php

namespace App\Repositories;

use App\Models\Status;

class StatusRepository
{
    protected $model;

    public function __construct(Status $status)
    {
        $this->model = $status;
    }

    /**
     * Get list of Statuses
     */
    public function getAssetStatuses()
    {
        return $this->model->get();;
    }
    
}