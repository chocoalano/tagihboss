<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TagihBossEmail;
use Illuminate\Support\Facades\Mail;
use App\User;
use Auth;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=[
            'nama'=>Auth::user()->name,
            'email'=>Auth::user()->email
        ];
        Mail::to("testing@tagihboss.com")->send(new TagihBossEmail($user));
        return "Email telah dikirim";
    }
}
