<?php

namespace App\Livewire;

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
            ->relationship(fn(): HasMany => $this->asset->assetlog())
            ->inverseRelationship('categories')
            ->columns([
                TextColumn::make('id')->sortable()->toggleable(),
                TextColumn::make('action_type')->sortable()->searchable()->toggleable(),
                TextColumn::make('created_at')->numeric()->dateTime('Y-m-d H:i:s')->sortable()->searchable()->toggleable(),
            ])
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
