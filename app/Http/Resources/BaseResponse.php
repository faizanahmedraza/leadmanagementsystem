<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource as Resource;
use App\Exceptions\V1\FailureException;

use Illuminate\Pagination\LengthAwarePaginator;

use Exception;
use Illuminate\Support\Facades\Auth;

class BaseResponse extends Resource
{
    private $error = null;
    private $message = "Operation successful";
    private $data = null;
    private $success = true;

    public function __construct($data, ?Exception $error = null, $success = true, $message = null)
    {
        $this->data = $data;
        $this->success = $success;
        $this->message = is_null($message) ? $this->message : $message;

        if (!is_null($error)) {
            $this->error = [
                'code' => $error->getCode(),
                'message' => $error->getMessage()
            ];
        } else {
            $this->error = (object)array();
        }

        parent::__construct($data);
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        throw FailureException::serverError();
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array
     */
    public function wrapped($response = [])
    {
        if ($this->resource instanceof LengthAwarePaginator) {
            $response['pagination'] = [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage()
            ];
        }


        if (\Auth::check() && $this->success) {
            $response['permissions'] = \Auth::user()->getAllPermissions()->pluck('name');
        }

        return [
            "data" => $response,
            "error" => $this->error,
            "success" => $this->success,
            "message" => $this->message
        ];
    }
}
