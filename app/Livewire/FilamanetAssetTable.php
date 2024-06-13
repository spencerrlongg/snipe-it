<?php

namespace App\Livewire;

use App\Helpers\Helper;
use App\Models\Asset;
use Filament\Tables\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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
            ->actions([
                Action::make('Checkout')
                    ->url(fn($record) => route('hardware.checkout.create', ['assetId' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                //this doesn't work, it's a post route so livewire redirectRoute doesn't work.
                //url doesn't work either because ->url() is generated on page load, so you can't pass in the user-selected arguments.
                //UGH
                //BulkAction::make('Edit')->action(function (Collection $records, Component $livewire): void {
                //    $livewire->redirectRoute(name: 'hardware/bulkedit', parameters: ['ids' => $records->pluck('id')]);
                //}),
                BulkAction::make('Edit')->action(fn($records) => $this->returnView($records)),
            ])
            ->filters([
                Filter::make('rtd')->label('RTD')
                    ->query(fn(Builder $query): Builder => $query->rtd())
            ])
            ->recordUrl(
                fn(Asset $record): string => route('tailwind.demo', $record->id),
            );

    }

    public function render(): View
    {
        return view('livewire.filamanet-asset-table');
    }

    public function returnView($records): View
    {
        $models = $records->unique('model_id');
        $modelNames = [];
        foreach ($models as $model) {
            $modelNames[] = $model->model->name;
        }
        return view('hardware/bulk')
            ->with('assets', $records->pluck('id'))
            ->with('statuslabel_list', Helper::statusLabelList())
            ->with('models', $models->pluck(['model']))
            ->with('modelNames', $modelNames);
    }
}
