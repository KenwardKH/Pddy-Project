<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Customer;

class SearchCustomer extends Component
{
    public $email = '';
    public $customerName = '';
    public $customerPhone = '';

    public function updatedEmail()
    {
        if ($this->email) {
            $user = User::where('email', $this->email)->with('customer')->first();
            
            if ($user && $user->customer) {
                $this->customerName = $user->customer->CustomerName;
                $this->customerPhone = $user->customer->CustomerContact;
            } else {
                // Debugging
                dd("User or customer not found", $user);
                $this->customerName = '';
                $this->customerPhone = '';
            }
        }
    }


    public function render()
    {
        return view('livewire.search-customer');
    }
}
