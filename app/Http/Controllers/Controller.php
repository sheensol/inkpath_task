<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	protected $data = [];
	protected $errors = [];
	protected $message = null;
	protected $statusCode = null;



	public function getMessage() {
		return $this->message;
	}

	/**
	 * @param null $message
	 */
	public function setMessage( $message ) {
		$this->message = $message;
	}

	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * @param null $message
	 */
	public function setStatusCode( $statusCode ) {
		$this->statusCode = $statusCode;
	}


	public function setErrors( array $errors ) {
		$this->errors = $errors;
	}

	public function getErrors(): array {
		return $this->errors;
	}

	/**
	 * @return array
	 */
	public function getData(): array {
		return $this->data;
	}

	/**
	 * @param array $data
	 */
	public function setData( array $data ) {
		$this->data = $data;
	}


	protected function validateReq( array $data, $for, $messages = [] ) {
		$rules     = $for;
		$validator = Validator::make( $data, $rules, $messages );

		if ( $validator->fails() ) {
			$this->errors = $validator->errors()->getMessages();

			return false;
		}

		return true;
	}


	public function response() {
		$resp = [
			'success'           => false,
			'errors'            => [],
			'data'              => null,

		];


		if ( count( $this->errors ) === 0 ) {
			$resp['success'] = true;
			$resp['message'] = $this->message;
			$resp['data']    = $this->data;


		} else {

			$resp['success'] = false;


			if ( is_array( $this->errors ) ) {
				foreach ( $this->errors as $error ) {
					if ( is_array( $error ) ) {
						foreach ( $error as $err ) {
							$resp['errors'][] = $err;
						}
					} else {
						$resp['errors'][] = $error;
					}
				}
			} else {
				$resp['errors'] = [ $this->errors ];
			}

		}



		return $resp;
	}


	public function getZones(){

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://hub.worldpop.org/rest/data/pop/wpgp?iso3=AUS',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response);
	}
}


