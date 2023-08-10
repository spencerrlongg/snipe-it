<?php

namespace App\Importer;

use App\Models\Asset;
use App\Models\Component;

class ComponentImporter extends ItemImporter
{
    public function __construct($filename)
    {
        parent::__construct($filename);
    }

    protected function handle($row)
    {
        parent::handle($row);
        $this->createComponentIfNotExists();
    }

    /**
     * Create a component if a duplicate does not exist
     *
     * @author Daniel Melzter
     * @since 3.0
     */
    public function createComponentIfNotExists()
    {
        $component = null;
        $this->log('Creating Component');
        $component = Component::where('name', $this->item['name'])
                        ->where('serial', $this->item['serial'])
                        ->first();

        if ($component) {
            $this->log('A matching Component '.$this->item['name'].' with serial '.$this->item['serial'].' already exists.  ');
            if (! $this->updating) {
                $this->log('Skipping Component');

                return;
            }
            $this->log('Updating Component');
            $component->update($this->sanitizeItemForUpdating($component));
            $component->save();

            return;
        }
        $this->log('No matching component, creating one');
        $component = new Component;
        $component->fill($this->sanitizeItemForStoring($component));
        //FIXME: this disables model validation.  Need to find a way to avoid double-logs without breaking everything.
        $component->unsetEventDispatcher();
        if ($component->save()) {
            //TODO this should move to the Loggable trait - and I need to figure out how to provide 'importer' context
            $component->logCreate('Imported using CSV Importer');
            $this->log('Component '.$this->item['name'].' was created');

            // If we have an asset tag, checkout to that asset.
            if (isset($this->item['asset_tag']) && ($asset = Asset::where('asset_tag', $this->item['asset_tag'])->first())) {
                $component->assets()->attach($component->id, [
                    'component_id' => $component->id,
                    'user_id' => $this->user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'assigned_qty' => 1, // Only assign the first one to the asset
                    'asset_id' => $asset->id,
                ]);
            }

            return;
        }
        $this->logError($component, 'Component');
    }
}
