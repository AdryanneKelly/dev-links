<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Dev extends Component
{
    public $dev;

    public function mount($nick)
    {
        $this->dev = User::where('nickname', $nick)->first();
        if (!$this->dev) {
            return redirect()->route('not-found');
        }
    }


    public function render()
    {
        return view('livewire.dev');
    }
}
