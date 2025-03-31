<?php

namespace PSW\Cinema\Customer\Http\Requests;

//use Illuminate\Foundation\Http\FormRequest;
//use PSW\Cinema\Core\Contracts\Validations\Release;
use Webkul\Core\Contracts\Validations\AlphaNumericSpace;
use Webkul\Core\Contracts\Validations\PhoneNumber;
// use Webkul\Customer\Rules\VatIdRule;

class CustomerReleaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'original_title'      => ['required',new AlphaNumericSpace],
            'other_title'         => [ new AlphaNumericSpace],
            'release_year'        => ['required', 'numeric'],
            'country'      => [new AlphaNumericSpace],
            'release_distribution'=> ['required', new AlphaNumericSpace],
            'releasetype'        => ['required',new AlphaNumericSpace],
            'language'    => ['required',new AlphaNumericSpace],
        ];
    }

    /**
     * Attributes.
     *
     * @return array
     */
    // public function attributes()
    // {
    //     // return [
    //     //     'address1.*' => 'release',
    //     // ];
    // }
}
