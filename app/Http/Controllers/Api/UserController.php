<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $user_query = User::query()->select('id','name','email','type' ,'mobile_number' , 'avatar')->get()->map(function ($user) {            
            $user->avatar = checkfile($user->avatar) ? getfile($user->avatar) : getfile('uploads/users-avatar/avatar.png');
            return $user;
        });
        
        if(isset($request['search']))
        {
            $search = $request['search'];    
            $user_query->where('name', 'like', "%$search%")->orwhere('email', 'like', "%$search%");
        }

        $users = $user_query->take(10);       
        $data  = ['users'=> $users];  

        return $this->success($data);
    }

    public function getuser(Request $request)
    {
        $user = User::find($request->id);

        if($user)
        {        
            $user->avatar = checkfile($user->avatar) ? getfile($user->avatar) : getfile('uploads/users-avatar/avatar.png');
            $data = ['users' => $user];              
            return $this->success($data);     
        }
        else
        {
            $message = "User does not exist";
            $data    = [];
            return $this->error($data , $message , 200);     
        }        
    }

    public function store(Request $request)
    {         
        $validator = Validator::make(
            $request->all(),
            [
                'name'                  => 'required|string|max:255',
                'email'                 => 'required|string|email|max:255|unique:users',
                'role'                  => 'required',
                'mobile_no'             => 'regex:/^\+\d{1,3}\d{9,13}$/',
                'password'              => 'required|min:6',
                'password_confirmation' => 'required|same:password',
                'avatar'                => 'required'
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        $role = Role::where('id', $request->role)->first();
        $post = [
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'type'            => isset($role) ? $role->name : '',
            'mobile_number'   => $request->mobile_no,
            'is_enable_login' => $request->password_switch == 'on' ? '1' : '0',
            'parent'          => creatorId(),
            'created_by'      => creatorId(),
        ];

        if($request->avatar)
        {
            $filenameWithExt = $request->file('avatar')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = uploadFile($request, 'avatar', $fileNameToStore, 'users-avatar', []);
            if ($path['flag'] == 1) {
                $url = $path['url'];
                $post['avatar'] = $url;
            }
        }
           
        $user = User::create($post);
        if ($role) {
            $user->addRole($role);
        }

        $data = [
            'user'=> $user,
        ]; 
        return $this->success($data);        
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $role = Role::where('id', $request->role)->first();
        if($user)
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'     => 'required|string|max:255',
                    'email'    => [
                        'required',
                        Rule::unique('users')->where(function ($query)  use ($user) {
                            return $query->whereNotIn('id', [$user->id])->where('parent',  Auth::user()->createId());
                        }),
    
                    ],
                    'mobile_no' => 'regex:/^\+\d{1,3}\d{9,13}$/',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                $data     = [];
                return $this->error($data , $messages->first() , 200);
            }

            if(User::where('email',$request->email)->exists() == true){
        
                $user->name          = $request->name;
                $user->type          = isset($role) ? $role->name : '';
                $user->mobile_number = $request->mobile_no;
                $user->password      =  Hash::make($request->password);

            }else{

                $user->name          = $request->name;
                $user->email         = $request->email;
                $user->type          = isset($role) ? $role->name : '';
                $user->mobile_number = $request->mobile_no;
                $user->password      =  Hash::make($request->password);
            }
            if($request->avatar)
            {
                $filenameWithExt = $request->file('avatar')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('avatar')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $path = uploadFile($request, 'avatar', $fileNameToStore, 'users-avatar', []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                    $user->avatar = $url;
                }
            }            
            $user->save();
            if ($role) {
                $user->roles()->sync($role);
            }
            $data = [
                'user'=> $user,
            ]; 
            return $this->success($data);
        }
        else{
            $message = "User does not exist";
            $data = [];
            return $this->error($data , $message , 200);
        }                
    }

    public function destroy(Request $request)
    {      
        $user = User::find($request->id);

        $data = [
            'user' => [],
        ]; 

        if($user)
        {
            $user->delete();
            return $this->success($data);      
        }
        else
        {
            $message = "User does not exist";
            return $this->error($data , $message , 200);      
        }
    }

    public function editProfile(Request $request)
    {
        if ($request->user_id) {
            $user = User::find($request->user_id);
        } elseif (Auth::user()) {
            $user = Auth::user();
        }

        $validator = Validator::make(
            $request->all(),
            [
                'name'     => 'required|string|max:255',
                'email'    => [
                    'required',
                    Rule::unique('users')->where(function ($query)  use ($user) {
                        return $query->whereNotIn('id', [$user->id])->where('parent',  Auth::user()->createId());
                    }),

                ],
                'mobile_no' => 'regex:/^\+\d{1,3}\d{9,13}$/',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        if ($user) {

            if($request->avatar)
            {
                $filenameWithExt = $request->file('avatar')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('avatar')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $path = uploadFile($request, 'avatar', $fileNameToStore, 'users-avatar', []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                    $user->avatar = $url;
                }
            }  
            $user->name          = $request->name;
            $user->email         = $request->email;
            $user->mobile_number = $request->mobile_no;
            $user->save();

            $user->avatar = checkfile($user->avatar) ? getfile($user->avatar) : getfile('uploads/users-avatar/avatar.png');
            $data = [
                'user'=> $user,
            ]; 
            return $this->success($data);
        }
        else{
            $message = "User does not exist";
            $data = [];
            return $this->error($data , $message , 200);
        }   
    }
}

