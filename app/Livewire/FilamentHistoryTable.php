<?php

namespace App\Livewire;

use App\Models\Actionlog;
use App\Tables\Columns\LinkColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Livewire\Component;
use App\Models\Asset;
use Livewire\WithPagination;


class FilamentHistoryTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Asset $asset;

    public function table(Table $table, $asset = null): Table
    {
        return $table
            ->relationship(fn(): HasMany => $this->asset->assetlog()->with(['admin', 'item.model', 'user', 'item']))
            ->columns([
                TextColumn::make('created_at')->numeric()->dateTime('Y-m-d H:i:s')->sortable()->searchable()->toggleable(),
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
                    ->sortable()->searchable()->toggleable()
            ])
            ->striped()
            ->selectable()
            ->filters([
                //
            ]);
        //return $table
        //    ->query($this->asset->actionlog)
        //    ->columns([
        //        TextColumn::make('name'),
        //    ])
        //    ->filters([
        //        // ...
        //    ])
        //    ->actions([
        //        // ...
        //    ])
        //    ->bulkActions([
        //        // ...
        //    ]);
    }

    public function render()
    {
        return view('livewire.filament-history-table');
    }
}
