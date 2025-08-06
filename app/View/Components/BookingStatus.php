<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BookingStatus extends Component
{
    public $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function badgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-700',
            'paid' => 'bg-green-100 text-green-700',
            'checked_in' => 'bg-blue-100 text-blue-700',
            'checked_out' => 'bg-gray-200 text-gray-700',
            'canceled' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function render()
    {
        return view('components.booking-status');
    }
}
