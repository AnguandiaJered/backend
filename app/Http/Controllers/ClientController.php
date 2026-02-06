<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Traits\JsonResponseTrait;

class ClientController extends Controller
{
      use JsonResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $client = Client::orderBy('id','desc')->paginate(5);
        return $this->sendData($client);
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
            'name' => 'required|unique:clients,name',
            'sexe' => 'nullable|string',
            'adresse' => 'nullable|string',
            'phone' => 'nullable|string',
        ]); //
    
       try {
            $client = new Client;

            $client->name = $request->input('name');
            $client->sexe = $request->input('sexe');
            $client->adresse = $request->input('adresse');
            $client->phone = $request->input('phone');
            $client->save();

            return $this->sendResponse($client, 'Enregistrement de client réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec d\'enregistrement', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::findOrFail($id); //
        return $this->sendData($client);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id); //
        return $this->sendData($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'=>'sometimes|string',
            'sexe' => 'sometimes|string',
            'adresse' => 'sometimes|string',
            'phone' => 'sometimes|string',
        ]);
         try {
            $client = Client::findOrFail($id); //

            $client->name = $request->input('name');
            $client->sexe = $request->input('sexe');
            $client->adresse = $request->input('adresse');
            $client->phone = $request->input('phone');
            $client->save();

            return $this->sendResponse($client, 'Modification de client réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec de modification', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Client::find($id)->delete();
        return $this->sendResponse('Suppression de client réussi');
    }
}
