<?php
namespace App\Http\sources;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\sources\Exceptions;

abstract class Request extends FormRequest{
    protected function failedValidation(Validator $validator)
    {
        if (isset($validator) AND $validator->fails()) {
            $fields = $validator->errors()->messages();
            
            foreach ($fields as &$field){
                $str = '';
                
                foreach ($field as $error){
                    $str .= $error.',';
                }

                $field = mb_substr($str, 0, -1);
            }
            
            throw new Exceptions(json_encode($fields, JSON_UNESCAPED_UNICODE), 400);
        }
    }
}