<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Livewire\Component;
use App\Models\Asset;


class FilamentHistoryTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $asset;

    public function table(Table $table, $asset = null): Table
    {
        return $table
            ->relationship(fn(): HasMany => $this->asset->assetlog()->with(['admin', 'item.model', 'user', 'item']))
            ->columns([
                TextColumn::make('created_at')->numeric()->dateTime('Y-m-d h:i:s a')->sortable()->searchable()->toggleable(),
                TextColumn::make('admin.first_name')->exists('admin')
                    ->getStateUsing(fn($record) => $record->admin->first_name.' '.$record->admin->last_name)
                    ->url(fn($record) => route('users.show', $record->admin->id))
                    ->sortable()->searchable()->toggleable(),
                TextColumn::make('action_type')->sortable()->searchable()->toggleable(),
                TextColumn::make('item')
                    ->getStateUsing(fn($record) => $record->item->model->name.' | ('.$record->item->asset_tag.')')
                    ->url(fn($record) => route('hardware.show', $record->item->id))
                    ->sortable()->searchable()->toggleable(),
                //TextColumn::make('log_meta')->sortable()->searchable()->sortable()->toggleable(),
                TextColumn::make('user.first_name')->exists('user')->label('Target')
                    ->url(fn($record) => $record->user ? route('users.show', $record->user->id) : null)
                    ->sortable()->searchable()->toggleable()
            ])
            ->striped()
            ->selectable()
            ->filters([
                SelectFilter::make('action_type')
                    ->options([
                        'checkout'      => 'Checkout',
                        'checkin from ' => 'Checkin',
                        'update'        => 'Update',
                    ])
            ])
            ->queryStringIdentifier('history');
    }

    public function render()
    {
        return view('livewire.filament-history-table');
    }
}
