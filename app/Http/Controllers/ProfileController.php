<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Traits\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileController extends Controller
{
    use Media;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile = User::where('id', Auth::id())->first();
        return view('profiles.index', ['profile' => $profile]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $profile = User::find($id);
        return view('profiles.edit', ['profile' => $profile]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $profile)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $profile->id,
            'password' => 'nullable',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:1024',
        ]);

        $profile->name = $request->name;
        $profile->email = $request->email;
        if ($request->password) $profile->password = bcrypt($request->password);

        if ($file = $request->file('image')) {
            $profile
                ->addMedia($file)
                ->usingName(str_replace(' ','-',$request->name))
                ->toMediaCollection('users');
        }

        $profile->save();
        alert()->success('Success', 'Data updated successfully');
        return redirect('profiles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
