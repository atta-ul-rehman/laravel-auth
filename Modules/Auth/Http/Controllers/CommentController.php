<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\Comment;
use Modules\Auth\Entities\jobCard;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Requests\commentVal;
use Modules\Auth\Http\Middleware\QueryParameters;

class CommentController extends Controller
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
        return view('auth::index');
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

        /**
         * @OA\Post(
         *     path="jobCard/{jobcard_id}/comment/create",
         *     summary="Create a new comment",
         *     tags={"JobCard/comment"},
         *     security={{"passport": {}}},
         *     @OA\Parameter(
         *         name="jobcard_id",
         *         in="path",
         *         description="ID of the job card",
         *         required=true,
         *         @OA\Schema(
         *             type="integer",
         *             format="int64"
         *         )
         *     ),
         *     @OA\RequestBody(
         *         @OA\JsonContent(),
         *         required=true,
         *         @OA\MediaType(
         *             mediaType="multipart/form-data",
         *             @OA\Schema(
         *                 @OA\Property(property="sent_from", type="string", example="sender@example.com"),
         *                 @OA\Property(property="sent_to", type="string", example="recipient@example.com"),
         *                 @OA\Property(property="message", type="string", example="This is a comment message"),
         *             ),
         *         ),
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Comment posted successfully",
         *         @OA\JsonContent(
         *             @OA\Property(property="success", type="boolean", example=true),
         *             @OA\Property(property="message", type="string", example="comment posted Successfully"),
         *             @OA\Property(property="data",  @OA\schema(ref="#/components/schemas/Comment")
         *              )
         *         )
         *     ),
         *     @OA\Response(
         *         response=404,
         *         description="Job card not found",
         *         @OA\JsonContent(
         *             @OA\Property(property="success", type="boolean", example=true),
         *             @OA\Property(property="message", type="string", example="No jobCard found with {jobcard_id} Id")
         *         )
         *     ),
         * )
         */

    public function store(commentVal $request, $jobcard_id)
    {
        $jobcard = jobCard::find($jobcard_id);
        if(!$jobcard){
            return response()->json([
                'success' => true,
                'message' => 'No jobCard found with ' .$jobcard_id. ' Id'
            ]);
        }
        $input = $request->validated();

        $input['commentable_id'] = (int)$jobcard_id;
        $input['commentable_type'] = jobCard::class;
        $input['user_id'] = $request->user()->id;
        $comment =  Comment::create($input);
        return response()->json([
            'success' => true,
            'message' => 'comment posted Successfully',
            'data' => $comment 
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        Comment::find($id)->commentable;
    }

    /**
 * @OA\Get(
 *     path="/jobCard/{jobcard_id}/comment/list",
 *     summary="Get comments by job card ID",
 *     tags={"JobCard/comment"},
 *     security={{"passport": {}}},
 *     @OA\Parameter(
 *         name="jobcard_id",
 *         in="path",
 *         description="ID of the job card",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comments retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data" , type="array", @OA\Items(@OA\schema(ref="#/components/schemas/Comment")))
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No job card available",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="string", example="No job cards are available")
 *         )
 *     ),
 * )
 */

    public function showByJobCard($jobcard_id)
    { 
       $data = jobCard::find($jobcard_id)?->comments;
      
       return response()->json([
        'success' => !count($data) == 0 ?? true,
        'data' => !count($data) == 0 ? $data : "No job card are available" 
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

    /**
     * @OA\Post(
     *     path="/comments/{id}/update?_method=PUT",
     *     summary="Update a comment",
     *     tags={"Comments"},
     *     security={{"passport": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the comment",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *  @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="sent_from", type="string", example="sender@example.com"),
     *                 @OA\Property(property="sent_to", type="string", example="recipient@example.com"),
     *                 @OA\Property(property="message", type="string", example="This is a comment message"),
     *             ),
     *         ),
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Comment Updated Successfully"),
     *             @OA\Property(property="data",  @OA\schema(ref="#/components/schemas/Comment")
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="User is not allowed to update this comment",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User is not allowed to update this comment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Comment with {id} not found")
     *         )
     *     ),
     * )
     */

    public function update(Request $request, $id)
    {
        $foundcomment = Comment::find($id);
    
        if (!$foundcomment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment with ' . $id . ' not found',
            ], 404);
        }
        $allowedUser = Comment::find($id)?->user;
        $loggedInUser = $request->user();
    
        if ($loggedInUser -> id !== $allowedUser['id']) {
            return response()->json([
                'success' => false,
                'message' => 'User is not allowed to update this comment',
            ], 403);
        }

        $foundcomment->update($request -> all());
        return response()->json([
            'success' => true,
            'message' => 'Comment Updated Successfully',
            'data' => $foundcomment
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

/**
 * Get the usr specific resource from storage.
 * @param int $user_id
 * @return Renderable
 */

/*
    * @OA\Get(
    *     path="/comments/user/list",
    *     summary="Get comments by user",
    *     tags={"Comments"},
    *     security={{"passport": {}}},
    * @OA\Parameter(
    *     name="select",
    *     in="query",
    *     description="Fields to select",
    *     @OA\Schema(
    *         type="string"
    *     )
    * ),
    *     @OA\Response(
    *         response=200,
    *         description="Comments retrieved successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="data", type="array", @OA\Items(@OA\schema(ref="#/components/schemas/Comment")))
    *         )
    *     ),
    *   @OA\Response(
    *         response=404,
    *         description="No comments found",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="data",  type="array", @OA\Items(@OA\schema(ref="#/components/schemas/Comment")))
    *         )
    *     ),
    * )
    * 
    */

    public function showByUser(Request $request)
    {
        try{
        $id = $request->user()->id;
        // Apply the middleware to the request
        $middleware = new QueryParameters($id, 'user_id');
        $request = $middleware->handle($request, function ($request) use ($middleware) {
            // Retrieve the advanced results from the request
            $advancedResults = $request->get('advancedResults', []);  
            // Extract the data from advanced results
            $data = $advancedResults['data'] ?? [];            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }, 'Comment');
    }
    catch (\Exception $e){
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ]);
    }
        // Return the modified request
        return $request;
    }
}


