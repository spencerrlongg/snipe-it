<?php

namespace App\Actions\Asset;

use App\Helpers\Helper;
use App\Http\Requests\ImageUploadRequest;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Company;
use App\Models\Location;
use App\Models\SnipeModel;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateAsset
{
    use AsAction;


    public function authorize(): bool
    {
        return Gate::allows('create', Asset::class);
    }

    public function handle($validatedAttributes): SnipeModel|bool
    {
        $asset = new Asset();
        $asset->model()->associate(AssetModel::find((int) $request->get('model_id')));

        $asset->name                    = $request->get('name');
        $asset->serial                  = $request->get('serial');
        $asset->company_id              = Company::getIdForCurrentUser($request->get('company_id'));
        $asset->model_id                = $request->get('model_id');
        $asset->order_number            = $request->get('order_number');
        $asset->notes                   = $request->get('notes');
        $asset->asset_tag               = $request->get('asset_tag', Asset::autoincrement_asset()); //yup, problem :/
        // NO IT IS NOT!!! This is never firing; we SHOW the asset_tag you're going to get, so it *will* be filled in!
        $asset->user_id                 = Auth::id();
        $asset->archived                = '0';
        $asset->physical                = '1';
        $asset->depreciate              = '0';
        $asset->status_id               = $request->get('status_id', 0);
        $asset->warranty_months         = $request->get('warranty_months', null);
        $asset->purchase_cost           = $request->get('purchase_cost');
        $asset->asset_eol_date          = $request->get('asset_eol_date', $asset->present()->eol_date());
        $asset->purchase_date           = $request->get('purchase_date', null);
        $asset->assigned_to             = $request->get('assigned_to', null);
        $asset->supplier_id             = $request->get('supplier_id');
        $asset->requestable             = $request->get('requestable', 0);
        $asset->rtd_location_id         = $request->get('rtd_location_id', null);
        $asset->location_id             = $request->get('rtd_location_id', null);


        /**
         * this is here just legacy reasons. Api\AssetController
         * used image_source  once to allow encoded image uploads.
         */
        if ($request->has('image_source')) {
            $request->offsetSet('image', $request->offsetGet('image_source'));
        }

        $asset = $request->handleImages($asset);

        // Update custom fields in the database.
        // Validation for these fields is handled through the AssetRequest form request
        $model = AssetModel::find($request->get('model_id'));

        if (($model) && ($model->fieldset)) {
            foreach ($model->fieldset->fields as $field) {

                // Set the field value based on what was sent in the request
                $field_val = $request->input($field->db_column, null);

                // If input value is null, use custom field's default value
                if ($field_val == null) {
                    \Log::debug('Field value for '.$field->db_column.' is null');
                    $field_val = $field->defaultValue($request->get('model_id'));
                    \Log::debug('Use the default fieldset value of '.$field->defaultValue($request->get('model_id')));
                }

                // if the field is set to encrypted, make sure we encrypt the value
                if ($field->field_encrypted == '1') {
                    \Log::debug('This model field is encrypted in this fieldset.');

                    if (Gate::allows('admin')) {

                        // If input value is null, use custom field's default value
                        if (($field_val == null) && ($request->has('model_id') != '')) {
                            $field_val = \Crypt::encrypt($field->defaultValue($request->get('model_id')));
                        } else {
                            $field_val = \Crypt::encrypt($request->input($field->db_column));
                        }
                    }
                }


                $asset->{$field->db_column} = $field_val;
            }
        }
        //TODO: refactor to a switch calling action classes
        if ($asset->save()) {
            if ($request->get('assigned_user')) {
                $target = User::find(request('assigned_user'));
            } elseif ($request->get('assigned_asset')) {
                $target = Asset::find(request('assigned_asset'));
            } elseif ($request->get('assigned_location')) {
                $target = Location::find(request('assigned_location'));
            }
            if (isset($target)) {
                $asset->checkOut($target, Auth::user(), date('Y-m-d H:i:s'), '', 'Checked out on asset creation', e($request->get('name')));
            }

            if ($asset->image) {
                $asset->image = $asset->getImageUrl();
            }
            return $asset;

            //
        }
        return false;

        //
    }


    public function jsonResponse()
    {
    }

    public function htmlResponse()
    {
    }
}
