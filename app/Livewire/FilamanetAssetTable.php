<?php

namespace App\Livewire;

use App\Models\Asset;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class FilamanetAssetTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Asset $asset;

    public function table(Table $table, $asset = null): Table
    {
        return $table
            ->query(Asset::query()->with(['model', 'company', 'model.category', 'assetstatus', 'location']))
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->toggleable(),
                TextColumn::make('company.name')->sortable()->searchable()->toggleable(),
                TextColumn::make('name')->sortable()->searchable()->toggleable(),
                ImageColumn::make('image')->getStateUsing(fn($record) => $record->model->getImageUrl())
                    ->sortable()->searchable()->toggleable(),
                TextColumn::make('asset_tag')->sortable()->searchable()->toggleable(),
                TextColumn::make('serial')->sortable()->searchable()->toggleable(),
                TextColumn::make('model.name')->sortable()->searchable()->toggleable(),
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
            ->striped();
    }

    public function render()
    {
        return view('livewire.filamanet-asset-table');
    }
}
