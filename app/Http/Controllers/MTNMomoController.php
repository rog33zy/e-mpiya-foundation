<?php

namespace App\Http\Controllers;

use App\ApiProvider;
use App\MtnProductSubscription;
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
     * Show the form for MTN Collections Widget.
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
     * Show the form for making MTN Collections.
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
     * Show the form for making MTN Disbursements.
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
     * Show the form for making MTN Remittances.
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

    /**
     * Show the form for MTN Settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiSettings()
    {
		// User
        $data['user'] = auth()->user();
        // API data
        $data['api_data'] = ApiProvider::all()->first();
        // header
        $data['header'] = 'MTN API Settings';
		
        return view('mtn.settings', $data);
    }
}
