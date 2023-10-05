<?php

namespace App\Actions\Assets;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateAsset
{
    use AsAction;

    public function handle(
                           $model_id = null,
                           $name = null,
                           $serial = null,
                           $company_id = null,
                           $asset_tag = null,
                           $order_number = null,
                           $notes = null,
                           $user_id = null,
                           $status_id = null,
                           $warranty_months = null,
                           $purchase_cost = null,
                           $asset_eol_date = null,
                           $purchase_date = null,
                           $assigned_to = null,
                           $supplier_id = null,
                           $requestable = null,
                           $rtd_location_id = null,
                           $location_id = null, //do something with this
    )
    {
        //So what do we do about attributes that aren't validated? Does this mean we always *have* to validate every attribute? yeah, probably
        //but maybe that's fine?
        $asset = Asset::create([
            'name' => $name,
            'serial' => $serial,
            'company_id' => Company::getIdForCurrentUser($company_id),
            'asset_tag' => $asset_tag ?? Asset::autoincrement_asset(),
            'order_number' => $order_number,
            'notes' => $notes,
            'user_id' => $user_id ?? Auth::id(),
            'archived' => '0',
            'physical' => '1',
            'depreciate' => '0',
            'status_id' => $status_id ?? 0,
            'warranty_months' => $warranty_months,
            'purchase_cost' => $purchase_cost,
            'purchase_date' => $purchase_date,
            'assigned_to' => $assigned_to,
            'supplier_id' => $supplier_id,
            'requestable' => $requestable ?? 0,
            'rtd_location_id' => $rtd_location_id,
            'location_id' => $rtd_location_id,
        ]);
        $model = AssetModel::find($model_id);
        $asset->model()->associate($model);
        $asset->asset_eol_date = $asset_eol_date ?? $asset->present()->eol_date();

        /**
         * this is here just legacy reasons. Api\AssetController
         * used image_source  once to allow encoded image uploads.
         */
        //TODO: hm, this is all based on the request, which we're not using anymore here...
        //maybe this gets moved up to the request level?
        //the way it's working in the first place is kind of a weird way to do it
        //if ($request->has('image_source')) {
        //    $request->offsetSet('image', $request->offsetGet('image_source'));
        //}
        //
        //$asset = $request->handleImages($asset);

        // Update custom fields in the database.
        // Validation for these fields is handled through the AssetRequest form request

        if (($model) && ($model->fieldset)) {
            foreach ($model->fieldset->fields as $field) {
                if ($field->field_encrypted == '1') {
                    if (Gate::allows('admin')) {
                        if ($model_id) {
                            $asset->{$field->db_column} = Crypt::encrypt($field->defaultValue($model_id));
                        } else {
                            $asset->{$field->db_column} = Crypt::encrypt($model_id);
                        }
                    }
                }
                //hmmmmm, this is an issue...
                //$field_val = $request->input($field->db_column, null);
                ////where's field_val coming from?
                //if ($field_val == null) {
                //    Log::debug('Field value for ' . $field->db_column . ' is null');
                //    $field_val = $field->defaultValue($validatedAttributesCollection->get('model_id'));
                //    Log::debug('Use the default fieldset value of ' . $field->defaultValue($validatedAttributesCollection->get('model_id')));
                //}
                //$asset->{$field->db_column} = $field_val;
            }
        }
        if ($asset->save()) {
            //TODO: refactor to a switch calling action classes
            //if ($validatedAttributesCollection->get('assigned_user')) {
            //    $target = User::find(request('assigned_user'));
            //}
            //elseif ($request->get('assigned_asset')) {
            //    $target = Asset::find(request('assigned_asset'));
            //} elseif ($request->get('assigned_location')) {
            //    $target = Location::find(request('assigned_location'));
            //}
            //if (isset($target)) {
            //    $asset->checkOut($target, Auth::user(), date('Y-m-d H:i:s'), '', 'Checked out on asset creation', e($request->get('name')));
            //}

            if ($asset->image) {
                $asset->image = $asset->getImageUrl();
            }
            return $asset;
        }
        return $asset;
    }
}
