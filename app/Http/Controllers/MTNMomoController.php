<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MTNMomoController extends Controller
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
     * Show the form for making MTN payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function collectionWidget()
    {
		// User
		$data['user'] = auth()->user();
        // header
        $data['header'] = 'Collections Widget Demo';
		
        return view('mtn.collection_widget', $data);
    }

    /**
     * Show the form for making MTN payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function collections()
    {
		// User
		$data['user'] = auth()->user();
        // header
        $data['header'] = 'Collections Demo';
		
        return view('mtn.collections', $data);
    }

    /**
     * Show the form for making MTN payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function disbursements()
    {
		// User
		$data['user'] = auth()->user();
        // header
        $data['header'] = 'Disbursements Demo';
		
        return view('mtn.disbursements', $data);
    }

    /**
     * Show the form for making MTN payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function remittances()
    {
		// User
        $data['user'] = auth()->user();
        // header
        $data['header'] = 'Remittances Demo';
		
        return view('mtn.remittances', $data);
    }
}
