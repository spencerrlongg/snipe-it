<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;

class PersonalAccessTokens extends Component
{
    public $name;
    public $newTokenString;

    protected $listeners = ['openModal' => 'autoFocusModalEvent'];

    //this is just an annoying thing to make the modal input autofocus
    public function autoFocusModalEvent(): void
    {
        $this->dispatchBrowserEvent('autoFocusModal');
    }

    public function render()
    {
        return view('livewire.personal-access-tokens', [
            'tokens' => Auth::user()->tokens,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function createToken(): void
    {
       $this->validate();

       $newToken = Auth::user()->createToken($this->name);

       $this->newTokenString = $newToken->accessToken;

       $this->dispatchBrowserEvent('tokenCreated', $newToken->accessToken);
    }

    public function deleteToken($tokenId): void
    {
        Auth::user()->tokens()->find($tokenId)->delete();
    }
}
