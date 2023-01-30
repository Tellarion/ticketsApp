<?php

namespace App\Http\Controllers;

use App\Customs\Tickets;

use Illuminate\Routing\Controller as BaseController;

use Validator;

class Api extends BaseController
{

    protected $tickets = "";

    public function __construct() {

        $this->tickets = new Tickets(env("TICKETS_API_URL", null), env("TICKETS_API_KEY", null));
        
    }

    public function addBooking() {

        $validate = Validator::make($_POST, [
            'eventId' => 'required|int',
            'username' => 'required|string',
            'seats' => 'required|string'
        ]);

        if($validate->fails()) {
            return json_encode(array('status' => false, 'message' => json_encode($validate->errors())));
        }

        $seats = json_decode($_POST['seats']);

        if(count($seats) >= 1) {
            $sendBooking = $this->tickets->bookingSeat($_POST['eventId'], $_POST['username'], $seats);
            // debug return json_encode(array('status' => true, 'message' => json_encode($sendBooking)));
        } else {
            return json_encode(array('status' => false, 'message' => 'Need select one seat or more'));
        }
        
        return json_encode(array('status' => true, 'message' => 'Success booking'));
    }

}