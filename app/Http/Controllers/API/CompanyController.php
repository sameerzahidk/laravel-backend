<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CompanyRequest;
use App\Notifications\NewCompanyNotification;
use Illuminate\Support\Facades\Notification;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Company::paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request)
    {
        $data = $request->validated();

    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('public/logos');
        $data['logo'] = Storage::url($logoPath);
    }

    $company = Company::create($data);
    $user = Company::where('email', $data['email'])->get(); // Modify this to fetch the admin users
    Notification::send($user, new NewCompanyNotification($company));

    return response()->json(['message' => 'Company added successfully', 'data' => $company], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

    return response()->json(['message' => 'Company deleted']);
    }
}
