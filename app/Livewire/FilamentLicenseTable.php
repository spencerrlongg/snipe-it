<?php

namespace App\Livewire;

use App\Models\Asset;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Livewire\Component;

class FilamentLicenseTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Asset $asset;

    public function table(Table $table, Asset $asset = null)
    {
        return $table
            ->relationship(fn(): BelongsToMany => $this->asset->licenses())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('serial')->label('Product Key'),
                TextColumn::make('expiration_date')->getStateUsing(fn($record) => $record->expiration_date->format('Y-m-d')),
            ])->actions([
                Action::make('checkin')
                    ->url(fn($record): string => route('licenses.checkin', $record->id))
            ])->striped();

    }

    public function render()
    {
        return view('livewire.filament-license-table');
    }
}
