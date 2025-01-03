<?php

namespace App\Http\sources;
use OpenApi\Attributes as OAT;
use App\Http\sources\ResourceController;


#[OAT\Info(
    version:"Pnzgu",
    title: "🎄Api Пензенского государственного университета",
    description: "Api Пензенского государственного университета"
)]
class Controller extends ResourceController{
    
}