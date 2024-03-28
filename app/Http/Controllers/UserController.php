<?php

namespace App\Http\Controllers;

use App\Models\User;
//use App\Services\userService;
use App\Services\UserService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->showUsers();

        return view('user-home', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $user = $this->userService->addUser($request);
            return response()->json(['message' => 'Data has been saved!', 'type' => $request->assetUser, 'id' => $user->id, 'emailId' => $user->email]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()]);
        } catch(Exception){
            return response()->json(['errors' => 'Failed to add user']);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param $request - form request
     * @param $user - User object
     */
    public function update(Request $request, User $user)
    {
        $this->userService->updateUser($request, $user);

        return response()->json(['message' => 'User has been updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param $user - User object
     */
    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);

            return response()->json(['success' => 'User Deleted Successfully!']);

        } catch (QueryException $e) {
            // Check if the exception is due to a unique constraint violation
            if ($e->getCode() === '23000') {
                return response()->json(['error' => 'An asset is added for the user, Please delete it first!!']);
            }else{
                return response()->json(['error' => 'An unexpected error occurred!!']);
            }
        }
    }

    /**
     * Get list of users
     * 
     */
    public function getUsers()
    {
        $users = $this->userService->getUsers();

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

}
