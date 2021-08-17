<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="ms-CRM OpenApi Documentation",
 *      description="ms-CRM OpenApi description",
 *      @OA\Contact(
 *          email="florian@alt.bzh"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 *
 * @OA\Server(
 *      url="http://127.0.0.1:8000/api/v1/",
 *      description="Full ms-CRM API endPoint documentation"
 * )

 *
 * @OA\Tag(
 *     name="ms-crm",
 *     description="API Endpoints of ms-CRM"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
