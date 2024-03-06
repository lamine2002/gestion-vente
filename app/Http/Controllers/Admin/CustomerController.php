<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerFormRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Customer::class, 'customer');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.customers.index',
            [
                'customers' => \App\Models\Customer::orderBy('created_at', 'desc')->paginate(25)
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.form',
            [
                'customer' => new \App\Models\Customer()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerFormRequest $request)
    {
        $customer = \App\Models\Customer::create($request->validated());
        return redirect()->route('admin.customers.index')->with('success', "Le client $customer->firstname $customer->lastname a été créé avec succès");
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('admin.customers.show',
            [
                'customer' => $customer
            ]
        );
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.form',
            [
                'customer' => $customer
            ]
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerFormRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        return redirect()->route('admin.customers.index')->with('success', "Le client $customer->firstname $customer->lastname a été modifié avec succès");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', "Le client $customer->firstname $customer->lastname a été supprimé avec succès");
    }

    public function exportPDF()
    {
        $customers = \App\Models\Customer::all();
        $pdf = \PDF::loadView('admin.customers.pdf', compact('customers'));
        return $pdf->download('customers.pdf');

    }

}
