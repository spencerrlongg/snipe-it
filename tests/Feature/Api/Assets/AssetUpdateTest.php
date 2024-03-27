<?php

namespace Tests\Feature\Api\Assets;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Company;
use App\Models\Location;
use App\Models\Statuslabel;
use App\Models\Supplier;
use App\Models\User;
use Tests\TestCase;

class AssetUpdateTest extends TestCase
{

    public function testThatANonExistentAssetIdReturnsError()
    {
        $this->actingAsForApi(User::factory()->editAssets()->createAssets()->create())
            ->patchJson(route('api.assets.update', 123456789))
            ->assertStatusMessageIs('error');
    }

    public function testRequiresPermissionToUpdateAsset()
    {
        $asset = Asset::factory()->create();

        $this->actingAsForApi(User::factory()->create())
            ->patchJson(route('api.assets.update', $asset->id))
            ->assertForbidden();
    }

    public function testGivenPermissionUpdateAssetIsAllowed()

    {
        $asset = Asset::factory()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson(route('api.assets.update', $asset->id), [
                'name' => 'test'
            ])
            ->assertOk();
    }

    public function testAllAssetAttributesAreStored()
    {
        $asset = Asset::factory()->create();
        $user = User::factory()->editAssets()->create();
        $userAssigned = User::factory()->create();
        $company = Company::factory()->create();
        $location = Location::factory()->create();
        $model = AssetModel::factory()->create();
        $rtdLocation = Location::factory()->create();
        $status = Statuslabel::factory()->create();
        $supplier = Supplier::factory()->create();

        $response = $this->actingAsForApi($user)
            ->patchJson(route('api.assets.update', $asset->id), [
                'asset_eol_date' => '2024-06-02',
                'asset_tag' => 'random_string',
                'assigned_user' => $userAssigned->id,
                'company_id' => $company->id,
                'last_audit_date' => '2023-09-03',
                'location_id' => $location->id,
                'model_id' => $model->id,
                'name' => 'A New Asset',
                'notes' => 'Some notes',
                'order_number' => '5678',
                'purchase_cost' => '123.45',
                'purchase_date' => '2023-09-02',
                'requestable' => true,
                'rtd_location_id' => $rtdLocation->id,
                'serial' => '1234567890',
                'status_id' => $status->id,
                'supplier_id' => $supplier->id,
                'warranty_months' => 10,
            ])
            ->assertOk()
            ->assertStatusMessageIs('success')
            ->json();

        $updatedAsset = Asset::find($response['payload']['id']);

        $this->assertEquals('2024-06-02', $updatedAsset->asset_eol_date);
        $this->assertEquals('random_string', $updatedAsset->asset_tag);
        $this->assertEquals($userAssigned->id, $updatedAsset->assigned_to);
        $this->assertTrue($updatedAsset->company->is($company));
        $this->assertTrue($updatedAsset->location->is($location));
        $this->assertTrue($updatedAsset->model->is($model));
        $this->assertEquals('A New Asset', $updatedAsset->name);
        $this->assertEquals('Some notes', $updatedAsset->notes);
        $this->assertEquals('5678', $updatedAsset->order_number);
        $this->assertEquals('123.45', $updatedAsset->purchase_cost);
        $this->assertTrue($updatedAsset->purchase_date->is('2023-09-02'));
        $this->assertEquals('1', $updatedAsset->requestable);
        $this->assertTrue($updatedAsset->defaultLoc->is($rtdLocation));
        $this->assertEquals('1234567890', $updatedAsset->serial);
        $this->assertTrue($updatedAsset->assetstatus->is($status));
        $this->assertTrue($updatedAsset->supplier->is($supplier));
        $this->assertEquals(10, $updatedAsset->warranty_months);
    }

    public function testAssetEolDateIsCalculatedIfPurchaseDateUpdated()
    {
        $asset = Asset::factory()->laptopMbp()->noPurchaseOrEolDate()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson((route('api.assets.update', $asset->id)), [
                'purchase_date' => '2021-01-01',
            ])
            ->assertOk()
            ->assertStatusMessageIs('success')
            ->json();

        $asset->refresh();

        $this->assertEquals('2024-01-01', $asset->asset_eol_date);
    }

    public function testAssetEolDateIsNotCalculatedIfPurchaseDateNotSet()
    {
        $asset = Asset::factory()->laptopMbp()->noPurchaseOrEolDate()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson(route('api.assets.update', $asset->id), [
                'name' => 'test asset',
                'asset_eol_date' => '2022-01-01'
            ])
            ->assertOk()
            ->assertStatusMessageIs('success')
            ->json();

        $asset->refresh();

        $this->assertEquals('2022-01-01', $asset->asset_eol_date);
    }

    public function testAssetEolExplicitIsSetIfAssetEolDateIsExplicitlySet()
    {
        $asset = Asset::factory()->laptopMbp()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson(route('api.assets.update', $asset->id), [
                'asset_eol_date' => '2025-01-01',
            ])
            ->assertOk()
            ->assertStatusMessageIs('success')
            ->json();

        $asset->refresh();

        $this->assertEquals('2025-01-01', $asset->asset_eol_date);
        $this->assertTrue($asset->eol_explicit);
    }

    public function testAssetTagCannotUpdateToNullValue()
    {
        $asset = Asset::factory()->laptopMbp()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson(route('api.assets.update', $asset->id), [
                'asset_tag' => null,
            ])
            ->assertOk()
            ->assertStatusMessageIs('error');
    }

    public function testAssetTagCannotUpdateToEmptyStringValue()
    {
        $asset = Asset::factory()->laptopMbp()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson(route('api.assets.update', $asset->id), [
                'asset_tag' => "",
            ])
            ->assertOk()
            ->assertStatusMessageIs('error');
    }

    public function testModelIdCannotUpdateToNullValue()
    {
        $asset = Asset::factory()->laptopMbp()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson(route('api.assets.update', $asset->id), [
                'model_id' => null
            ])
            ->assertOk()
            ->assertStatusMessageIs('error');
    }

    public function testModelIdCannotUpdateToEmptyStringValue()
    {
        $asset = Asset::factory()->laptopMbp()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson(route('api.assets.update', $asset->id), [
                'model_id' => ""
            ])
            ->assertOk()
            ->assertStatusMessageIs('error');
    }

    public function testStatusIdCannotUpdateToNullValue()
    {
        $asset = Asset::factory()->laptopMbp()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson(route('api.assets.update', $asset->id), [
                'status_id' => null
            ])
            ->assertOk()
            ->assertStatusMessageIs('error');
    }

    public function testStatusIdCannotUpdateToEmptyStringValue()
    {
        $asset = Asset::factory()->laptopMbp()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson(route('api.assets.update', $asset->id), [
                'status_id' => ""
            ])
            ->assertOk()
            ->assertStatusMessageIs('error');
    }

    public function testIfRtdLocationIdIsSetWithoutLocationIdAssetReturnsToDefault()
    {
        $location = Location::factory()->create();
        $asset = Asset::factory()->laptopMbp()->create([
            'location_id' => $location->id
        ]);
        $rtdLocation = Location::factory()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson(route('api.assets.update', $asset->id), [
                'rtd_location_id' => $rtdLocation->id
            ]);

        $asset->refresh();

        $this->assertTrue($asset->defaultLoc->is($rtdLocation));
        $this->assertTrue($asset->location->is($rtdLocation));
    }

    public function testIfLocationAndRtdLocationAreSetLocationIdIsLocation()
    {
        $location = Location::factory()->create();
        $asset = Asset::factory()->laptopMbp()->create();
        $rtdLocation = Location::factory()->create();

        $this->actingAsForApi(User::factory()->editAssets()->create())
            ->patchJson(route('api.assets.update', $asset->id), [
                'rtd_location_id' => $rtdLocation->id,
                'location_id' => $location->id
            ]);

        $asset->refresh();

        $this->assertTrue($asset->defaultLoc->is($rtdLocation));
        $this->assertTrue($asset->location->is($location));
    }
}