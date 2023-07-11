<?php

namespace Modules\Auth\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
* @OA\Info(
*      version="1.0.0",
*      title="Laravel OpenApi Demo Documentation",
*      description="L5 Swagger OpenApi description",
*      @OA\Contact(
*          email="admin@admin.com"
*      ),
*      @OA\License(
*          name="Apache 2.0",
*          url="http://www.apache.org/licenses/LICENSE-2.0.html"
*      )
* )php
*
* @OA\Server(
*      url=L5_SWAGGER_CONST_HOST,
*      description="Demo API Server"
* )
*
* @OA\Tag(
*     name="Projects",
*     description="API Endpoints of Projects"
* )
*/

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}