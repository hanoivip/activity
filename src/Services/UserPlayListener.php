<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Events\Game\UserPlay;
use Exception;
use Illuminate\Support\Facades\Log;

class UserPlayListener
{
    protected $data;
    
    protected $builder;
    
    protected $types = ['login'];
    
    public function __construct(
        IActivityDataService $data,
        ActivityBuilder $builder)
    {
        $this->data = $data;
        $this->builder = $builder;
    }
    
    public function handle(UserPlay $event)
    {   
        try
        {
            
        }
        catch (Exception $ex)
        {
            Log::error("Activity process UserPlay event error! Ex:" . $ex->getMessage());
        }
    }
}