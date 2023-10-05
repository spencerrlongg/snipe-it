<?php

namespace App\Actions\Assets;

use Lorisleiva\Actions\Concerns\AsAction;

class CheckOutAssetToUser
{
    use AsAction;

    public function handle($asset, $user, $notes = null)
    {
        CreateAsset::run(
            id: $id,
        );
    }
}
