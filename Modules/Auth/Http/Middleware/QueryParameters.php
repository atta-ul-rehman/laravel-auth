<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Str;
use Modules\Auth\Entities\User;
use Spatie\Permission\Models\Role;

class queryParameters
{
    protected $idValue;
    public $idKey;  

    public function __construct($idValue = null, $idKey = null)
    {
        $this->idKey = $idKey;
        $this->idValue = $idValue;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \string  $model
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next, $model)
    {
        $modelClass = "Modules\Auth\Entities\\" . $model;
      
        if (!class_exists($modelClass) || !is_subclass_of($modelClass, Model::class)) {
            abort(400, "Invalid model name provided");
        }
        $queryParams = $request->query();

        // Validate the query string parameters.
        $this->validateQueryParams($queryParams);

        // If the query string parameters are valid, store them in the request object.
        $request->merge($queryParams);

        // Create a new Eloquent query instance.
        $query = $modelClass::query();

        // Apply user-specific company-specific etc condition
        if ($model == 'Comment' && $this->idKey) {
            $query->where($this->idKey, $this->idValue);
        }
        
        // Select Fields
        if ($request->filled('select')) {
            try {
                $select = $request->input('select');

                if ($select) {
                    $fields = explode(',', $select);
                    // Check if 'message' field is included in the select fields
                    $query->select($fields);
                 
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
                return response()->json(['error' => $error], 400);
            }
        }
        

        // Sort
        if ($request->filled('sort')) {
            $sortBy = $request->input('sort');

            if ($sortBy) {
                $sortFields = explode(',', $sortBy);
                foreach ($sortFields as $sortField) {
                    $sortField = trim($sortField);
                    $direction = 'asc';
                    if (str_starts_with($sortField, '-')) {
                        $sortField = substr($sortField, 1);
                        $direction = 'desc';
                    }
                    $query->orderBy($sortField, $direction);
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }
        }
            // Pagination
            // $page = $request->input('page', 1);
            // $limit = $request->input('limit', 25);
            // $startIndex = ($page - 1) * $limit;
            // $endIndex = $page * $limit;

            // $total = $query->count();
            // $query->skip($startIndex)->take($limit);
            // Execute the query
            $perPage = $request->input('per_page', 10);
    $results = $query->paginate($perPage);
    // Modify the response as needed
   // dd($results->perPage());
    $responseData = [
        'success' => true,
        'data' => $results->items(),
        'pagination' => [
            'current_page' => $results->currentPage(),
            'per_page' => $results->perPage(),
            'total' => $results->total(),
        ],
    ];

          
            // Pagination result
            $pagination = [];

            // if ($endIndex < $total) {
            //     $pagination['next'] = [
            //         'page' => $page + 1,
            //         'limit' => $limit,
            //     ];
            // }

            // if ($startIndex > 0) {
            //     $pagination['prev'] = [
            //         'page' => $page - 1,
            //         'limit' => $limit,
            //     ];
           // }
            //if model is User select only Stewards
            if ($model == 'User') {
                $results = $results->role('Steward')->get();
            }
           
            $request->merge([
                'advancedResults'=> $responseData
            ]);

            return $next($request);
        }

    private function validateQueryParams(array $queryParams)
    {
        // Check if the query string parameters are empty.
        if (empty($queryParams)) {
            return;
        }

        // Check if the query string parameters contain invalid characters.
        foreach ($queryParams as $key => $value) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
                // $response = [
                //     'error' => true,
                //     'message' => "The query string parameter '$key' contains invalid characters."
                // ];

                // abort(400, response()->json($response));
                throw new InvalidArgumentException("The query string parameter '$key' contains invalid characters.");
            }
        }
    }
}
