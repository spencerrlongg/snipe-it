<?php

namespace Tests\Feature\ActionLogs;

use App\Models\Actionlog;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Statuslabel;
use App\Models\User;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    public function testActionLogIsCreatedAtApiAssetStore()
    {
        $user = User::factory()->createAssets()->create();
        $model = AssetModel::factory()->create();
        $status = Statuslabel::factory()->create();

        $this->settings->enableAutoIncrement();

        $response = $this->actingAsForApi($user)->postJson(route('api.assets.store'), [
            'model_id'  => $model->id,
            'status_id' => $status->id,
        ])->assertOk()->assertStatusMessageIs('success')->json();

        $asset = Asset::find($response['payload']['id']);

        $log = $asset->assetlog()->sole();

        $this->assertNotNull($log);

        $this->assertEquals('create', $log->action_type);
    }

    public function testActionLogIsCreatedAtGuiAssetStore()
    {
        $this->markTestIncomplete('not finished');

        $user = User::factory()->createAssets()->create();
        $model = AssetModel::factory()->create();
        $status = Statuslabel::factory()->create();

        $this->settings->enableAutoIncrement();

        $response = $this->actingAs($user)->postJson(route('assets.store'), [
            'model_id'  => $model->id,
            'status_id' => $status->id,
        ])->assertOk()->assertStatusMessageIs('success')->json();

        $asset = Asset::find($response['payload']['id']);
        $log = Actionlog::where('item_id', $asset->id)->where('item_type', 'App\Models\Asset')->first();

        $this->assertEquals('create', $log->action_type);
    }
}
