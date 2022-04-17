<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CustomGuestUserValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
      if($value !== config('const.GUEST_USER_Email')) {
        return true;
      } else {
        return false;
      }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
      return trans('validation.gestUserEmail');
    }
}
