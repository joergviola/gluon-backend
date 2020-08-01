<?php

namespace Gluon\Backend\Controllers;

use Gluon\Backend\Api\API;
use Gluon\Backend\Events\ApiAfterLoginEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        return view('login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            \Log::info('Login attempt failed', $credentials);
            if ($request->isJson()) {
                return response()->json(['message'=>'Access denied'], 403);
            } else {
                return redirect("/login");
            }
        }
        return $this->checkLogin($request);
    }
    public function checkLogin(Request $request) {
        if (!Auth::check()) {
            \Log::info('Not logged in');
            if ($request->isJson()) {
                return response()->json(['message'=>'Access denied'], 403);
            } else {
                return redirect("/login");
            }
        }
        $user = Auth::user();
        $user->token = $user->createToken('Personal')->accessToken;
        $roles = API::query('role', [
            'and' => ['id' => $user->role_id ],
            'with' => ['rights' => ['many' => 'right'] ]
        ]);
        $user->role = $roles[0];
        $user->documents = API::query('document', [
            'and' => ['type' => 'users', 'item_id' => $user->id ],
        ]);
        event(new ApiAfterLoginEvent($user));
        \Log::info('Login attempt successful', [$user->email]);
        if ($request->isJson()) {
            return response()->json($user);
        } else {
            session(['token' => $user->token]);
            return redirect("/schema");
        }
    }

    public function register(Request $request) {
        return DB::transaction(function() use ($request) {
            $orgname = $request->input('orgname');
            $name = $request->input('name');
            $email = $request->input('email');
            $pwd = $request->input('password');

            $client = API::provider('client')->insertGetId([
                'name' => $orgname
            ]);
            $role = API::provider('role')->insertGetId([
                'name' => 'Admin',
                'client_id' => $client,
            ]);
            API::provider('right')->insertGetId([
                'role_id' => $role,
                'client_id' => $client,
                "tables" => "*",
                "columns" => "*",
                "where" => "all",
                "actions" => "CRUD",
            ]);
            $org = API::provider('organisation')->insertGetId([
                'name' => $orgname,
                'client_id' => $client,
            ]);
            API::provider('users')->insertGetId([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($pwd),
                'client_id' => $client,
                'role_id' => $role,
                'organisation_id' => $org,
            ]);
            return response()->json(['success'=>true]);
        });
    }
}

