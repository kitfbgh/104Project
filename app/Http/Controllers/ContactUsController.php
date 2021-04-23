<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Mail;

class ContactUsController extends Controller
{
    public function index(Request $request) {
        return view('user.contact');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|max:30',
            'email' => 'required|email|max:50',
            'phone' => 'required|string|max:15',
            'subject'=>'required|string',
            'message' => 'required|string'
         ]);
  
        ContactUs::create($request->all());
  
        \Mail::send('mail', array(
              'name' => $request->get('name'),
              'email' => $request->get('email'),
              'phone' => $request->get('phone'),
              'subject' => $request->get('subject'),
              'msg' => $request->get('message'),
          ),
          function($message) use ($request){
              $message->from($request->email);
              $message->to('natz.liutest@gmail.com', 'Hello Admin!')->subject($request->get('subject'));
          });      
  
        return redirect()->back()->with('success', '感謝您的聯繫！');
    }
}
