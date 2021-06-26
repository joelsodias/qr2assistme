<?php

namespace App\Models;

use App\Models\BaseModel;

class TicketModel extends BaseModel
{
    protected $table      = 'ticket';
    protected $returnType     = 'App\Entities\TicketEntity';

}