<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AssetParameterService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $assetParameterService;

    public function __construct(AssetParameterService $assetParameterService)
    {
        $this->assetParameterService = $assetParameterService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->assetParameterService->showUsers();
        
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
        $user = $this->assetParameterService->addUser($request);
        
        return response()->json(['message' => 'Data has been saved!', 'type' => $request->assetUser, 'id' => $user->id, 'emailId' => $user->email ]);
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
     */
    public function update(Request $request, User $user)
    {
        $this->assetParameterService->updateUser($request, $user);

        return response()->json(['message' => 'User has been updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->assetParameterService->deleteUser($user);

        return response()->json(['success' => 'Type Deleted Successfully!']);
    }
    
}
