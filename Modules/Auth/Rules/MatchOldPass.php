<?php

namespace Modules\Auth\Rules;

use Illuminate\Contracts\Validation\validationRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MatchOldPass implements ValidationRule
{
     /**
     * Create a new rule instance.
     *
     * @return void
     */
    
    public function validate($attribute, $value, $fail):void {

        if(!Hash::check($value, Auth::user()->password))
        {
            $fail('The :attribute does not match with old password');
        }
    }

}
