<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\Company;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Middleware\queryParameters;
use Spatie\Permission\Models\Role;

class CompanyController extends Controller
{
    /**
     * Initialize the roles and permissions needed for this controller.
     * @return Renderable
     */
    public function __construct()
    {
        $this->middleware('permission:create-user', ['only' => ['store']]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        // $data = Company::orderBy('id')->paginate($request->input('per_page', 2));
        // if (!$data) {
        //     return response()->json([
        //         'success' => false,
        //         'data' => 'No company found'
        //     ]);
        // }
        // return response()->json([
        //     'success' => true,
        //     'data' => $data
        // ]);

        $middleware = new queryParameters();
        $request = $middleware->handle($request, function ($request) use ($middleware) {
            $advancedResults = $request->get('advancedResults', []); 
            //dd($advancedResults);
            $data = $advancedResults ?? [];
            return response()->json([
                'success' => !count($data) == 0  ?? true ,
                'data' => !count($data) == 0 ? $data : 'No company found'
            ]);
        }, 'Company');
        return $request;
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
        try{
        $data = [
            'name' => $request->name,
            'user_id' => $request->user()->id,
        ];
        $company = Company::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Company Created Successfully',
            'data' => $company
        ]);
    }
    catch(\Exception $e){
        return response()->json([
            'success' => false,
            'data' => $e->getMessage()
        ]);
    }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('auth::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
    {
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = Company::find($id);
        $data->name = $request->name;
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Company Updated Successfully',
            'data' => $data
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
