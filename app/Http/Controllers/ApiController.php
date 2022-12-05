<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\PrettyJsonResponse;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    //

	public function getApiData(Request $request): JsonResponse{

		// get data from Api
	   $response = $this->getZones();
	   if (!empty($response->data)){

		   $resobj  = 	collect($response->data);
	    if ($request->has('popyear') && !empty($request->get('popyear'))){
	    	   $resobj = collect($response->data)->where('popyear',$request->get('popyear'));
	    }
	   	$this->setMessage("All Zone");
	   	$this->setData($resobj->toArray());


	   }else{

	   	$this->setErrors([$response->detail]);
	   	}

           return new PrettyJsonResponse( $this->response());

	}
}
