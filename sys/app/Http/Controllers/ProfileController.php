<?php

namespace App\Http\Controllers;

use Gate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
      auth()->user()->update($request->all());
        $user_id=auth()->user()->id;
         $request->validate([
            'files.*' => 'required|file|mimes:jpeg,png,jpg|max:20480', // Example validation rules
        ]);
          if ($request->hasFile('files'))  {      
$user=User::find($user_id);
            $files = $request->file('files');
            $cc=count($files);
        foreach($files as $file){
            
                $filename = $file->getClientOriginalName();
                $file->storeAs('uploads', $filename);        
                $file->move(public_path('assets/img'), $filename);  
            $extension = $file->getClientOriginalExtension();
            $user->logo=$filename;                   
            $user->save();
             }
                  //return redirect()->back()->with('success','Details uploaded successfully.');
         
        }

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }
}
