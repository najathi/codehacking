<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UsersRequest;
use App\Photo;
use App\User;
use App\Role;
use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $users = User::paginate(5);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //$roles = Role::lists('name', 'id')->all(); // ! laravel 5.2
        $roles = Role::pluck('name', 'id')->all(); // ! laravel 5.3
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
    {
        //
        //return $request->all();

        //$input = $request->all();

        //User::create($request->all());
        //redirect('admin/users');

        if (trim($request->password) == '') {
            $input = $request->except('password');
            // except() - Get all of the input except for a specified array of items.
        } else {
            $input = $request->all();
            $input['password'] = bcrypt($request->password);
        }

        if ($file = $request->file('photo_id')) {
            //return 'Photo exist';

            $name = time() . $file->getClientOriginalName();

            //move the file
            $file->move('images', $name);

            // insert data into photos table 
            $photo = Photo::create(['file' => $name]);

            //get the photo_id of photos table
            $input['photo_id'] = $photo->id;
        }

        // encrypt the password
        //$input['password'] = bcrypt($request->password);

        //dd($input);
        // insert data into users table
        User::create($input);

        // redirect
        return redirect('admin/users')->with('success', 'The User has been created');
    }

    /**
     * Display the specified resource. 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return view('admin.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $user = User::findOrFail($id);

        //$roles =  Role::lists('name', 'id')->all(); // ! laravel 5.2
        $roles =  Role::pluck('name', 'id')->all(); // ! laravel 5.3

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserEditRequest $request, $id)
    {
        //

        //return $request->all();

        $user = User::findOrFail($id);

        /* if (trim($request->password) == '') {
            $input = $request->except('password');
        } else {
            $input = $request->all();
            $input['password'] = bcrypt($request->password);
        } */

        $input = $request->all();

        if ($file = $request->file('photo_id')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('images', $name);

            $photo = Photo::create(['file' => $name]);

            $input['photo_id'] = $photo->id;
        }

        $input['password'] = bcrypt($request->password);

        $user->update($input);

        //dd($input);

        return redirect('admin/users')->with('info', 'The User has been updated');
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
        //return 'DESTROY';

        // delete the user only 
        //User::findOrFail($id)->delete();

        // find the user
        $user = User::findOrFail($id);

        //delete the image of user
        //unlink(public_path() . '/images/' . $user->photo->file); // '/images' - it doesn't needed because i already created accessor
        unlink(public_path() . $user->photo->file);

        // find the photo data of user
        $photo = Photo::findOrFail($user->photo_id);

        // delete photo data
        $photo->delete();

        // delete user
        $user->delete();

        // flash message
        //Session::flash('deleted_user', 'The User has been deleted');


        return redirect('admin/users')->with('warning', 'The User has been deleted');
    }
}
