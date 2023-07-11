<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Auth\Entities\Company;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Requests\stewardRegVal;
use Spatie\Permission\Models\Role;


class StewardController extends Controller
{
     /**
     * Initialize the roles and permissions needed for this controller.
     * @return Renderable
     */

    public function __construct()
    {
        $permissions = Config::get('customPermissions')['StewardController'];
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
        $usersWithRole = Role::where('name', 'Steward')->first();
        $usersCollection = $usersWithRole::role($usersWithRole)->get();
       
        return response()->json([
            'success' => !empty($usersCollection) ?? true,
            'data' => !empty($usersCollection) ? $usersCollection : 'No Stewards found',
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
    public function store(stewardRegVal $request)
    { 
        $input = $request->validated();
        $input['created_by'] = $request->user()->id;
        $Steward = User::create($input);
        return response()->json([
            'success' => true,
            'message' => 'Steward Created Successfully',
            'data' => $Steward
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $Steward = User::find($id);
        return response()->json([
            'success' => !empty($Steward) ?? true,
            'data' => !empty($Steward) ? $Steward : "No Stewards found"
        ]);
    }

     /**
     * Show the specified resource through its parent resource
     * @param int $company_id
     * @return Renderable
     */
    public function showByCompany($company_id)
    {
        $data = [];
        $Steward = Company::findorFail($company_id)?->user;
        forEach($Steward as $user)
        {
            if($user->hasRole('Steward'))
            array_push($data, $user);
        }
      
        return response()->json([
            'success' =>  !empty($data) ?? true,
            'data' => !empty($data) ? $data : "No Stewards found"
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
    public function update(Request $request, $user)
    {
        $loggedInUser = $request->user();
        $foundUser = User::find($user);
    
        if (!$foundUser) {
            return response()->json([
                'success' => false,
                'message' => 'Steward not found',
            ], 404);
        }
    
        if ($loggedInUser->id !== (int)$foundUser->created_by) {
            return response()->json([
                'success' => false,
                'message' => 'User is not allowed to update',
            ], 403);
        }

        $foundUser->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Steward Updated Successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function delete(Request $request, $user)
    {
        $loggedInUser = $request->user();
        $foundUser = User::find($user);
    
        if (!$foundUser) {
            return response()->json([
                'success' => false,
                'message' => 'Steward not found',
            ], 404);
        }
    
        if ($loggedInUser->id !== $foundUser->created_by) {
            return response()->json([
                'success' => false,
                'message' => 'User is not allowed to delete',
            ], 403);
        }
    
        $deletedRows = User::destroy($foundUser->id);
    
        return response()->json([
            'success' => $deletedRows !== 0,
            'message' => $deletedRows ? 'Steward Deleted Successfully' : 'Steward not found',
        ]);
    }
}
