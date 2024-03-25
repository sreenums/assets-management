<?php

namespace App\Services;


use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
  
    /**
     * Show list of users
     * 
     */
    public function showUsers()
    {
        return $this->userRepository->showUsers();
    }

    /**
     * Add User to storage
     * 
     * @param $request - form request data
     */
    public function addUser($request)
    {
        $validatedData = $request->validate([
            'assetUser' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
        ]);
        
        $data = ['name' => $request->assetUser, 'email' => $request->email];
        $locationData = ['name' => $request->assetUser, 'type' => 'user'];
        
        /**
         * To save user locations
         */
        //$locationUser = $this->locationRepository->addLocation($locationData);

        return $this->userRepository->addUser($data);
    }

    /**
     * Delete User from storage
     * 
     * @param $location - Location object
     */
    public function deleteUser($location)
    {
        return $this->userRepository->deleteUser($location);
    }

    /**
     * Update User to storage
     * 
     * @param $request - form request data
     * @param $user - User object
     */
    public function updateUser($request, $user)
    {
        $validatedData = $request->validate([
            'editUserName' => 'required|string|max:255',
            'editEmail' => 'required|email|max:255',
        ]);

        $userData = [
            'name' => $request->editUserName,
            'email' => $request->editEmail,
        ];

        return $type = $this->userRepository->updateUser($user, $userData);
    }


        /**
     * Get list of users
     */
    public function getUsers()
    {
        return $this->userRepository->getUsers();
    }
}