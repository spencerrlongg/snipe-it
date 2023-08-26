<?php

namespace App\Http\Requests;

use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreAssetRequest extends ImageUploadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        //TODO: make sure this works
         return Gate::allows('create', new Asset);
    }

    public function prepareForValidation(): void
    {
        //this turns the singular asset_tag into an array of asset_tags even though we only have one to clean the action
        if($this->has('asset_tag')) {
            $this->merge([
                'asset_tags' => [$this->get('asset_tag')]
            ]);
        }
        //if any request attributes start with "_snipeit_", merge them into the rules array
        //and get the validation rules from the db model
        //actually, looks like brady's new trait might handle this and we can remove custom field validation from the actions
        //foreach ($this->all() as $key => $value) {
        //    if (str_starts_with($key, '_snipeit_')) {
        //        $this->merge([
        //            $key  => $value
        //        ]);
        //    }
        //}
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(
            //from the model validation rules
            (new Asset)->getRules(),
            //from the parent class (ImageUploadRequest)
            parent::rules(),
            [
                'asset_tags' => 'array',
                'asset_tags.*' => 'string|max:255',
            ]
        );
    }
}
