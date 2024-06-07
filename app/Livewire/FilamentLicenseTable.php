<?php

namespace App\Livewire;

use App\Models\Asset;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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
                TextColumn::make('Product Key'),
            ]);

    }

    public function render()
    {
        return view('livewire.filament-license-table');
    }
}
