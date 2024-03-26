<?php

namespace App\Http\Responses\Landlord\Home;

use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable
{
    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        $payload = $this->payload;

        return view('landlord/dashboard/index', compact('page', 'stats', 'payload'));
    }

}
