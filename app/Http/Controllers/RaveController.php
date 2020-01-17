<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RaveController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for MTN Collections Widget.
     *
     * @return \Illuminate\Http\Response
     */
    public function inline()
    {
		// User
		$data['user'] = auth()->user();
        // header
        $data['header'] = 'Rave Inline Demo';
		
        return view('rave.inline', $data);
    }

    /**
     * Show the form for making MTN Collections.
     *
     * @return \Illuminate\Http\Response
     */
    public function standard()
    {
		// User
		$data['user'] = auth()->user();
        // header
        $data['header'] = 'Rave Standard Demo';
		
        return view('rave.standard', $data);
    }
}
