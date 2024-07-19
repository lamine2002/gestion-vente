<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerFormRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerFormRequest $request)
    {
        $customer = Customer::create($request->validated());
        return response()->json([
            'message' => "Le client $customer->firstname $customer->lastname a été créé avec succès",
            'customer' => $customer
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerFormRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        return response()->json([
            'message' => "Le client $customer->firstname $customer->lastname a été modifié avec succès",
            'customer' => $customer
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // on ne peut pas supprimer un client qui a des commandes
        if ($customer->orders->count() > 0) {
            return response()->json([
                'message' => "Le client $customer->firstname $customer->lastname ne peut pas être supprimé car il a des commandes"
            ], 400);
        }

        $customer->delete();
        return response()->json([
            'message' => "Le client $customer->firstname $customer->lastname a été supprimé avec succès"
        ]);
    }

    /**
     * Export customers to PDF.
     */
    public function exportPDF()
    {
        $customers = Customer::all();
        $pdf = \PDF::loadView('admin.customers.pdf', compact('customers'));
        return $pdf->download('customers.pdf');
    }
}
