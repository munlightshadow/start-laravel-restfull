<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ProductTypeRule implements Rule
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
        if(!($value = json_decode($value))){
            return false;
        }
        foreach($value as $tag => $slugs){
            if(is_array($slugs)){
                foreach($slugs as $slug){
                    if(!array_has(config('type'),strtoupper($tag).'.'.strtoupper($slug))){
                        return false;
                    }
                }
            }else{
                if(!array_has(config('type'),strtoupper($tag).'.'.strtoupper($slugs))){
                    return false;
                }
            }

        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The key and value in types not exists.';
    }
}
