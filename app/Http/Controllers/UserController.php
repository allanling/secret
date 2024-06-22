<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function signup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:users,user_id|min:6|max:20',
            'password' => 'required|alpha_dash:ascii|min:8|max:20',
        ], $messages = [
            'unique' => 'already same user_id is used',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => "Account creation failed",
                'cause' => $validator->errors()->first()
            ]);
        }

        $user = User::create([
            'user_id' => $request->input('user_id'),
            'nickname' => $request->input('user_id'),
            'password' => Hash::make($request->input('password')),
            'comment' => ''
        ]);

        return response()->json([
            'message' => "Account successfully created",
            'user' => ['user_id' => $user['user_id'], 'nickname' => $user['nickname']]
        ]);
    }

    public function show(string $userId, Request $request)
    {
        $foundUser = User::where('user_id', $userId)
            ->first();
        if (!$foundUser) {
            return response()->json(['message' => 'No User found'], 404);
        } else {
            $formattedUser['user_id'] = $foundUser->user_id;
            $formattedUser['nickname'] = $foundUser->nickname;
            if ($foundUser->comment) {
                $formattedUser['comment'] = $foundUser->comment;
            }
            return response()->json(['message' => 'User details by user_id', 'user' => $formattedUser]);
        }
    }

    public function update(string $userId, Request $request)
    {

        $user = Auth::user();
        if ($user && $userId !== $user->user_id) {
            return response()->json(['message' => 'No Permission for Update'], 403);
        }

        if ($request->input('user_id') || $request->input('password')) {
            return response()->json(
                ['message' => 'User updation failed', 'cause' => 'not updatable user_id and password'],
                400
            );
        }

        $validator = Validator::make($request->all(), [
            'nickname' => 'max:30|required_without:comment',
            'comment' => 'max:100|required_without:nickname',
        ], $messages = [
            'required_without' => 'required nickname or comment',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => "User updation failed",
                'cause' => $validator->errors()->first()
            ]);
        }

        $foundUser = User::where('user_id', $userId)
            ->first();
        if (!$foundUser) {
            return response()->json(['message' => 'No User found'], 404);
        } else {
            $toUpdate['nickname'] =  $request->input('nickname') ? $request->input('nickname') : $foundUser->user_id;
            if ($request->has('comment')) {
                $toUpdate['comment'] = $request->input('comment');
            }
            $foundUser->update($toUpdate);

            return response()->json(['message' => 'User successfully updated', 'recipe' => [$toUpdate]]);
        }
    }

    public function close()
    {
        $user = Auth::user();
        $user->delete();
        return response()->json(['message' => 'Account and user successfully removed']);
    }
}
