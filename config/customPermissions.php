<?php
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\CompanyController;
use Modules\Auth\Http\Controllers\StewardController;

return [
        'AuthController' => [
            'Login' => ['view-users'],
            'register' => ['view-users'],
            'forgetPassword' => ['view-users'],
            'resetPassword' => ['view-users'],
            'changePassword' => ['view-users'],
            'logout' => ['view-users'],
            'getUser' => ['view-users'],
         ],
        'CompanyController' => [
            'store' => ['create-users'],
            'index' => ['view-users'],
        ],
        'StewardController' => [
            'store' => ['view-users'],
            'index' => ['create-users'],
            'show' => ['view-users'],
            'showByCompany' => ['view-users'],
            'update' => ['edit-users'],
            'delete' => ['delete-users'],
        ],
        'JobCardController' => [
            'store' => ['create-jobCard'], 
            'index' => ['create-jobCard'],
            'show' => ['create-jobCard'],
            'update' => ['create-jobCard'],
            'showByCompany' => ['create-jobCard'],
            'delete' => ['create-jobCard'],
        ],
        'CommentController' =>[
            'showByJobCard' => ['create-jobCard'],
            'showByUser' => ['create-jobCard'],
            'store' => ['create-jobCard'], 
            'index' => ['create-jobCard'],
            'show' => ['create-jobCard'],
            'update' => ['create-jobCard'],
        ],
        'DutyRosterController' => [
            'showByJobCard' => ['create-jobCard'],
            'showByUser' => ['create-jobCard'],
            'store' => ['create-jobCard'], 
            'index' => ['create-jobCard'],
            'show' => ['create-jobCard'],
            'update' => ['create-jobCard'], 
        ]
        ];