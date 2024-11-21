<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class SearchCustomer extends Component
{
    public $email;
    public $name;
    public $phone;
    public $customerId;

    public function cariAkun()
    {
        $user = User::with('customer')->where('email', $this->email)->first();

        if ($user && $user->customer) {
            $this->name = $user->customer->CustomerName;
            $this->phone = $user->customer->CustomerContact;
            $this->customerId = $user->customer->CustomerID;
        } else {
            $this->name = null;
            $this->phone = null;
            $this->customerId = null;
        }
    }

    public function render()
    {
        return view('livewire.search-customer');
    }
}
