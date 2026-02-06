<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\User;

class ProduitController extends Controller
{
    use JsonResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perteprod = PerteProduit::with(['category','user'])->orderBy('id','desc')->paginate(5);
        return $this->sendData($perteprod);
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
            'name' => 'required|unique:produits,name',
            'category_id' => 'required',
        ]);
       
        try {
            $product = new Produit(); //

            $product->name = $request->input('name');
            // $product->quantity = $request->input('quantity');
            $product->quantity = 0;
            $product->category_id = $request->input('category_id');
            $product->author_id = Auth::user()->id;
            $product->save();

            return $this->sendResponse($product, 'Enregistrement de produit réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec d\'enregistrement', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Produit::findOrFail($id); //
        return $this->sendData($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Produit::findOrFail($id); //
        return $this->sendData($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'quantity' => 'sometimes|integer',
            'category_id' => 'sometimes|integer',
        ]);
       
        try {
            $product = Produit::findOrFail($id); //

            $product->name = $request->input('name');
            // $product->quantity = $request->input('quantity');
            $product->quantity = 0;
            $product->category_id = $request->input('category_id');
            $product->author_id = Auth::user()->id;
            $product->save();

            return $this->sendResponse($product, 'Modification de produit réussi');
        } catch (\Exception $ex) {
            return $this->sendErrorResponse('Echec de modification', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Produit::find($id)->delete();
        return $this->sendResponse('Suppression de produit réussi');
    }
}
