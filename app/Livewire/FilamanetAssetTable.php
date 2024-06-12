<?php

namespace App\Livewire;

use App\Models\Asset;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\View\View;
use Livewire\Component;

class FilamanetAssetTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $asset;

    public function table(Table $table, $asset = null): Table
    {
        if (is_null($this->asset)) {
            $asset = Asset::query()->with(['model', 'company', 'model.category', 'assetstatus', 'location']);
        } elseif ($this->asset->assignedAssets->count() > 0) {
            // having to put this here because for some reason it seems like livewire won't take a query builder instance as a prop
            $asset = $this->asset->assignedAssets?->toQuery();
        } else {
            $asset = Asset::query()->where('id', 0);
        }
        return $table
            ->query($asset)
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->toggleable(),
                TextColumn::make('company.name')->sortable()->searchable()->toggleable(),
                TextColumn::make('name')->sortable()->searchable()->toggleable(),
                ImageColumn::make('image')->getStateUsing(fn($record) => $record->model->getImageUrl())
                    ->sortable()->searchable()->toggleable(),
                TextColumn::make('asset_tag')->sortable()->searchable()->toggleable(),
                TextColumn::make('serial')->sortable()->searchable()->toggleable(),
                TextColumn::make('model.name')->sortable()->searchable()->toggleable()
                    ->url(fn($record) => route('models.show', $record->model->id)),
                TextColumn::make('model.model_number')->label('Model No.')->sortable()->searchable()->toggleable(),
                TextColumn::make('model.category.name')->sortable()->searchable()->toggleable(),
                TextColumn::make('assetstatus.name')->sortable()->searchable()->toggleable(),
                // i'm getting some of these columns wrong, will come back
                TextColumn::make('assignedTo.first_name')->getStateUsing(fn($record) => $record->assignedTo?->first_name.' '.$record->assignedTo?->last_name)
                    ->searchable()->toggleable(),
                TextColumn::make('assignedTo.employee_num')->sortable()->searchable()->toggleable(),
                TextColumn::make('location.name')->sortable()->searchable()->toggleable(),
            ])
            ->selectable()
            ->striped()
            ->recordUrl(
                fn(Asset $record): string => route('tailwind.demo', $record->id),
            );
    }

    public function render(): View
    {
        return view('livewire.filamanet-asset-table');
    }
}
