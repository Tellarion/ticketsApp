<?php

namespace App\Http\Controllers;

use App\Customs\Tickets;

use Illuminate\Routing\Controller as BaseController;

class Index extends BaseController
{

    protected $tickets = "";

    public function __construct() {

        $this->tickets = new Tickets(env("TICKETS_API_URL", null), env("TICKETS_API_KEY", null));
        
    }

    public function default() {

        $shows = $this->tickets->shows();

        return view('pages.events', ['shows' => $shows]);
    }

    public function showDetail($id = null) {

        if($id != null) {

            $showsDetails = $this->tickets->showEvents($id);

            return view('pages.detailsShow', ['showDetail' => $showsDetails]);
        } else {
            return redirect()->route('/');
        }
    }

    public function booking($id = null) {

        if($id != null) {

            $array = (object) array();

            $array->id = $id;

            $array->places = array();
            $array->places = json_encode($this->tickets->showPlaces($array->id)->response);

            //print_r($array);

            return view('pages.booking', ['booking' => $array]);
        } else {
            return redirect()->route('/');
        }
    }
}
