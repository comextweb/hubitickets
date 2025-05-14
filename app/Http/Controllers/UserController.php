<?php

namespace App\Http\Controllers;

use App;
use App\Events\CreateUser;
use App\Events\DestroyUser;
use App\Events\UpdateUser;
use App\Http\Requests\UserAddRequest;
use App\Models\User;
use App\Models\Category;
use App\Models\UserCatgory;
use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\LoginDetails;
use App\Models\Role;
use App\Models\SubCategory;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('2fa');
        // $this->authorizeResource(User::class);
    }


    public function index()
    {
        if (Auth::user()->isAbleTo('user manage')) {
            $users = User::where('created_by', creatorId())->get();
            return view('admin.users.index', compact('users'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }


    public function create()
    {
        if (Auth::user()->isAbleTo('user create')) {
            // $categories = Category::where('created_by', creatorId())->get();
            // $categoryTree = buildCategoryTree($categories);
            $roles = Role::where('created_by', creatorId())->get();
            return view('admin.users.create', compact('roles'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function show()
    {
        abort(403, 'Page Not Found !');
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('user create')) {
            $request->validate([
                'name'    => 'required|string|max:255',
                'email'   => 'required|string|email|max:255|unique:users',
                'role' => 'required',
            ]);
            if ($request->avatar) {
                $request->validate([
                    'avatar'    => 'required|image',
                ]);
            }

            if (!empty($request->password_switch) && $request->password_switch == 'on') {
                $request->validate([
                    'password' => 'required|min:6',
                    'password_confirmation' => 'required|same:password'
                ]);
            }

            $role = Role::where('id', $request->role)->first();
            $settings  = getCompanyAllSettings(creatorId());
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile_number = $request->mobile_no;
            $user->password = !empty($request->password) ? Hash::make($request->password) : null;
            $user->parent = creatorId();
            $user->is_enable_login = $request->password_switch == 'on' ? '1' : '0';
            $user->type = isset($role) ? $role->name : '';
            $user->lang = isset($settings['default_language']) ? $settings['default_language'] : 'en';
            $user->created_by = creatorId();
            if ($request->hasFile('avatar')) {
                $filenameWithExt = $request->file('avatar')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('avatar')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $url = '';
                $path = uploadFile($request, 'avatar', $fileNameToStore, 'users-avatar', []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                    $user->avatar =  $url;
                    $user->save();
                } else {
                    return redirect()->back()->with('error', __($path['msg']))->withInput();
                }
            }
            $user->save();

            if ($role) {
                $user->addRole($role);
            }
            event(new CreateUser($user, $request));

            if (isset($settings['New User']) && $settings['New User'] == 1) {
                $uArr = [
                    'email' => $user->email,
                    'password' => $request->password,
                ];
                $resp = Utility::sendEmailTemplate('New User', [$user->id => $user->email], $uArr);
            }
            return redirect()->route('admin.users')->with('success', __('User created successfully') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function edit(User $user)
    {
        if (Auth::user()->isAbleTo('user edit')) {
            $categories = Category::where('created_by', creatorId())->get();
            // $categoryTree = buildCategoryTree($categories);
            $roles = Role::where('created_by', creatorId())->get();
            return view('admin.users.edit', compact('user',   'roles', 'categories'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->isAbleTo('user edit')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    Rule::unique('users')->where(function ($query)  use ($user) {
                        return $query->whereNotIn('id', [$user->id])->where('parent',  Auth::user()->createId());
                    }),

                ],
            ]);
            $role = Role::where('id', $request->role)->first();
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->mobile_number = $request->mobile_no;
            $user->type = isset($role) ? $role->name : '';


            if ($request->hasfile('avatar')) {
                $request->validate(['avatar' => 'required|image']);
                $filenameWithExt = $request->file('avatar')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('avatar')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $url = '';

                $path = uploadFile($request, 'avatar', $fileNameToStore, 'users-avatar', []);

                if ($path['flag'] == 1) {
                    // Old img delete
                    if (!empty($user['avatar']) && strpos($user['avatar'], 'avatar.png') == false && checkFile($user['avatar'])) {
                        deleteFile($user['avatar']);
                    }

                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                $user->avatar = $url;
            }
            $user->save();
            event(new UpdateUser($user, $request));
            if ($role) {
                $user->roles()->sync($role);
            }
            return redirect()->route('admin.users')->with('success', __('User Updated Successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }


    public function destroy(User $user)
    {
        if (Auth::user()->isAbleTo('user delete')) {
            event(new DestroyUser($user));
            // delete the role of the user role_user table
            $user->roles()->detach();
            if ($user->avatar) {
                $removeImage = deleteFile($user->avatar);
            }
            $user->delete();

            return redirect()->route('admin.users')->with('success', __('User deleted Successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function roles()
    {
        return response()->json(Role::get());
    }





    public function userlog(Request $request)
    {
        if (Auth::user()->isAbleTo('userlog manage')) {
            $objUser = Auth::user();
            $date = new DateTime($request->month);

            $usersList = User::where('parent', '=', $objUser->createId())->get()->pluck('name', 'id');
            $usersList->prepend('All User', '');

            if ($request->month == null) {
                $users = DB::table('login_details')
                    ->join('users', 'login_details.user_id', '=', 'users.id')
                    ->select(DB::raw('login_details.*, users.name as user_name , users.email as user_email'))
                    ->where(['login_details.created_by' => $objUser->id])
                    ->whereMonth('login_details.date', date('m'))
                    ->whereYear('login_details.date', date('Y'));
            } else {
                $users = DB::table('login_details')
                    ->join('users', 'login_details.user_id', '=', 'users.id')
                    ->select(DB::raw('login_details.*, users.name as user_name , users.email as user_email'))
                    ->where(['login_details.created_by' => $objUser->id]);
            }

            if (!empty($request->month)) {
                $users->whereMonth('date', $date->format('m'));
                $users->whereYear('date', $date->format('Y'));
            }
            if (!empty($request->user)) {
                $users->where(['user_id'  => $request->user]);
            }
            $users = $users->orderBy('id', 'desc')->get();

            return view('admin.users.userLog', compact('users', 'usersList'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }


    public function userlogview($id)
    {
        if (Auth::user()->isAbleTo('userlog show')) {
            $userlog = LoginDetails::find($id);
            return view('admin.users.viewUserLog', compact('userlog'));
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }

    public function userlogDestroy($id)
    {
        if (Auth::user()->isAbleTo('userlog delete')) {
            $userlog = LoginDetails::find($id);
            if ($userlog) {
                $userlog->delete();
                return redirect()->back()->with('success', 'User Log Deleted Successfully.');
            } else {
                return redirect()->back()->with('error', 'Userlog Not Found.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function LoginManage($id)
    {
        if (Auth::user()->isAbleTo('user login manage')) {
            $eId = Crypt::decrypt($id);
            $user = User::find($eId);
            if ($user->is_enable_login == 1) {
                $user->is_enable_login = 0;
                $user->save();
                return redirect()->route('admin.users')->with('success', 'User login disable successfully.');
            } else {
                $user->is_enable_login = 1;
                $user->save();
                return redirect()->route('admin.users')->with('success', 'User login enable successfully.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function userPassword($id)
    {
        if (Auth::user()->isAbleTo('user login manage') || Auth::user()->isAbleTo('user reset password')) {
            $eId  = Crypt::decrypt($id);
            $user = User::find($eId);

            $employee = User::where('id', $eId)->first();

            return view('admin.users.reset', compact('user', 'employee'));
        } else {
            return response()->json(['error' => 'Permission Denied.'], 401);
        }
    }


    public function userPasswordReset(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('user login manage') || Auth::user()->isAbleTo('user reset password')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'password' => 'required|confirmed|same:password_confirmation',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $user      = User::where('id', $id)->first();
            $user->forceFill([
                'password' => Hash::make($request->password),
            ])->save();
            return redirect()->route('admin.users')->with('success', 'User Password successfully updated.');
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function profile()
    {
        if (Auth::user()->isAbleTo('user profile manage')) {
            $user = Auth::user();

            $google2fa = new \PragmaRX\Google2FAQRCode\Google2FA();
            $google2fa_url = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $user->google2fa_secret
            );
            $secret_key = $user->google2fa_secret;
            $data = [
                'user' => $user ?? '',
                'secret' => $secret_key,
                'google2fa_url' => $google2fa_url,
            ];

            return view('admin.users.profile', compact('user', 'data'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }


    public function editprofile(Request $request,  $id)
    {
        if (Auth::user()->isAbleTo('user profile manage')) {

            $user = User::findOrFail($id);

            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'mobile_number' => 'required'
            ]);

            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'required|confirmed',
                    'password_confirmation' => 'required',
                ]);
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('avatar')) {
                $request->validate(['avatar' => 'required|image']);
                $filenameWithExt = $request->file('avatar')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('avatar')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $path = uploadFile($request, 'avatar', $fileNameToStore, 'users-avatar', []);

                // Old img delete
                if (!empty($user['avatar']) && strpos($user['avatar'], 'avatar.png') == false && checkFile($user['avatar'])) {
                    deleteFile($user['avatar']);
                }

                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->route('admin.users', Auth::user()->id)->with('error', __($path['msg']));
                }
                $user->avatar = $url;
            }
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->mobile_number = $request->mobile_number;
            $user->save();

            return redirect()->back()->with('success', __('User updated successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied');
        }
    }
    public function updatePassword(Request $request)
    {
        if (Auth::user()->isAbleTo('user profile manage')) {
            if (\Auth::Check()) {
                $request->validate(
                    [
                        'current_password' => 'required',
                        'new_password' => 'required|min:6',
                        'confirm_password' => 'required|same:new_password',
                    ]
                );
                $objUser          = Auth::user();
                $request_data     = $request->All();
                $current_password = $objUser->password;
                if (Hash::check($request_data['current_password'], $current_password)) {
                    $user_id            = Auth::User()->id;
                    $obj_user           = User::find($user_id);
                    $obj_user->password = Hash::make($request_data['new_password']);;
                    $obj_user->save();

                    return redirect()->route('profile', $objUser->id)->with('success', __('Password updated successfully'));
                } else {
                    return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
                }
            } else {
                return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
