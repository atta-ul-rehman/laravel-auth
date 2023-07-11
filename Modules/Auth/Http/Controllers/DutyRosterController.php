<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\dutyRoster;
use Modules\Auth\Entities\jobCard;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Requests\updateDutyRosterVal;

class DutyRosterController extends Controller
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
    public function index()
    {
        $data = dutyRoster::all();
       
        return response()->json([
            'success' => !count($data) == 0?? true,
            'data' => !count($data) == 0? $data : 'No jobCard found',
        ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = dutyRoster::find($id);
        return response()->json([
         'success' => !count($data) == 0?? true,
         'data' => !count($data) == 0? $data : "No dutyRoster are available for '$id' id" 
     ]);
    }

    /**
     * Show the specified resource through its parent resource
     * @param int $user_id
     * @return Renderable
     */
    public function showByUser($user_id)
    { 
       $data = User::find($user_id)?->dutyRoster;
     
       return response()->json([
        'success' => !count($data) == 0?? true,
        'data' => !count($data) == 0? $data : "No dutyRoster are available for '$user_id' user" 
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
    public function update(updateDutyRosterVal $request, $id)
    {
        $dutyRoster = dutyRoster::find($id);
        
        if (!$dutyRoster) {
            return response()->json([
                'success' => false,
                'message' => 'DutyRoster with ' . $id . ' not found',
            ], 404);
        }
        $allowedUser = dutyRoster::find($id)->user;
        
        $loggedInUser = $request->user();
    
        if ($loggedInUser -> id !== $allowedUser ->id) {
            return response()->json([
                'success' => false,
                'message' => 'User is not allowed to update the steward',
            ], 403);
        }
            
        //$request->validated();  
        $dutyRoster->update($request -> all());
        return response()->json([
            'success' => true,
            'message' => 'DutyRoster Updated Successfully',
            'data' => $dutyRoster
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
