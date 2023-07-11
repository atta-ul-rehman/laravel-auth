<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\Company;
use Modules\Auth\Entities\dutyRoster;
use Modules\Auth\Entities\jobCard;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Requests\jobCardVal;
use Modules\Auth\Http\Requests\updateJobCardVal;
use Modules\Auth\Transformers\jobCardResource;

class JobCardController extends Controller
{
      /**
     * Initialize the roles and permissions needed for this controller.
     * @return Renderable
     */

     public function __construct()
     {
         $permissions = config('customPermissions')['JobCardController'];
         $controllerMethod = config('currentController.controllerMethod')();
         if (!empty($permissions[$controllerMethod]) && isset($permissions[$controllerMethod])) {
             $this->middleware('permission:' . $permissions[$controllerMethod][0], ['only' => [$controllerMethod]]);
         }
     }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data = jobCard::all();
       
        return response()->json([
            'success' => !count($data) == 0 ?? true,
            'data' => !count($data) == 0? $data : 'No jobCard found',
        ]);

        // if ($results) {
        //     $success = $results['success'];
        //     $count = $results['count'];
        //     $pagination = $results['pagination'];
        //     $data = $results['data'];
        //     return response()->json([
        //         'success' => $success,
        //         'count' => $count,
        //         'pagination' => $pagination,
        //         'data' => $data,
        //     ]);
        // }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('auth::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(jobCardVal $request, $company_id)
    {
        $company = Company::find($company_id);
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => "No Company found with ".$company_id." Id",
            ]);
          }
        $user = $request->user();
        // dd($request->user()->id, $company->user_id);
        if($request->user()->id !== (int)$company->created_by){
            return response()->json([
                'success' => false,
                'message' => 'User is not allowed to create job card',
            ], 404);
        }

        $input = $request->validated();
        $input['company_id'] = $company_id;
        $input['user_id'] = $user->id;
        
        $JobCard= jobCard::create($input);
        return response()->json([
            'success' => true,
            'message' => 'Job Card Created Successfully',
            'data' => $JobCard
        ]);
       } 

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
       $data = jobCard::find($id);
       return response()->json([
        'success' => !count($data) == 0  ?? true,
        'data' => !count($data) == 0  ? $data : "No job card are available for '$id' id" 
    ]);
    }
    
    /**
     * Show the specified resource through its parent resource
     * @param int $company_id
     * @return Renderable
     */ 
    public function showByCompany($company_id)
    { 
       $data = jobCardResource::collection(Company::find($company_id)?->jobCard);
       return response()->json([
        'success' => !count($data) == 0  ?? true,
        'data' => !count($data) == 0  ? $data : "No job card are available for '$company_id' company" 
    ]);
    }
    
     /**
     * Show the specified resource through its parent resource
     * @param int $dutyRoster_id
     * @return Renderable
     */
    public function showBydutyRoster($dutyRoster_id)
    { 
       $data = dutyRoster::find($dutyRoster_id) ?->jobCard;
       return response()->json([
        'success' => !count($data) == 0 ?? true,
        'data' => !count($data) == 0 ? $data : "No job card are available for '$dutyRoster_id' dutyRoster" 
    ]);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('auth::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(updateJobCardVal $request, $id)
    {
        $foundCard = jobCard::find($id);
    
        if (!$foundCard) {
            return response()->json([
                'success' => false,
                'message' => 'Job card with ' . $id . ' not found',
            ], 404);
        }
        $allowedUser = jobCard::find($id)->user;
        
        $loggedInUser = $request->user();
    
        if ($loggedInUser -> id !== $allowedUser ->id) {
            return response()->json([
                'success' => false,
                'message' => 'User is not allowed to update the steward',
            ], 403);
        }

        $foundCard->update($request -> validated());
        return response()->json([
            'success' => true,
            'message' => 'Job Card Updated Successfully',
            'data' => $foundCard
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function delete(Request $request , $id)
    {
        $loggedInUser = $request->user();
        $foundCard = jobCard::find($id)?->user;

        if (!$foundCard) {
            return response()->json([
                'success' => false,
                'message' => 'Jobcard user not found',
            ], 404);
        }
    
        if ($loggedInUser?->id !== $foundCard?->created_by) {
            return response()->json([
                'success' => false,
                'message' => 'User is not allowed to delete',
            ], 403);
        }
    
        $deletedRows = jobCard::destroy($id);
    
        return response()->json([
            'success' => $deletedRows !== 0,
            'message' => $deletedRows ? 'Jobcard Deleted Successfully' : 'Jobcard not found',
        ]);
    }
}
