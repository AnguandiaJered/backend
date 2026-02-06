<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Models\User;

class UserController extends Controller
{  
    use JsonResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::latest()->paginate(5);
        return $this->sendData($user);
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
        $request->validate([
            'name' => 'required',
            'email' => 'sometimes|email|unique:users',
            'password' => 'required|min:6',
            'role'=>'nullable|string',
        ]);
        
        // $user = User::where('phone', User::getParsedPhone('phone'))->orWhere('email', $request->input('email'))->first();
        $user = User::findForSanctum($request->input('email'));

        if (!$user) {
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = User::getParsedPhone('phone');
            $user->country_code = User::getParsedCountryCode('phone');
            $user->password = bcrypt($request->input('password'));
            $user->role = $request->input('role');
            $user->active = true;
            $user->verified = false;
            $user->save();

            //lancer l'evenent en fin d'ecouter et envoyer l'email
            event(new Registered($user));

            return $this->sendResponse($user, 'Création compte réussi');
        }
        return $this->sendErrorResponse('Ce numéro ou email existe déjà dans notre base de données');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return $this->sendData($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return $this->sendData($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
            'password' => 'sometimes|string',
            'role'=>'nullable|string',
        ]);
          try {
           $user = User::findOrFail($id);

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->role = $request->input('role');
            $user->save();

            return $this->sendResponse($user, 'Modification de user réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec de modification', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::find($id)->delete();
        return $this->sendResponse('Suppression de User réussi');
    }
}
