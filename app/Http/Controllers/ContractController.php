<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    //GET
    public function showContract()
    {
        $users = User::where('role_id', 3)->get(); // zakelijke gebruikers
        return view('contracts.upload-contract', compact('users'));
    }

    //POST
    public function storeContract(Request $request)
    {
        $messages = [
            'name.required' => 'Naam is verplicht.',
            'name.string' => 'Naam moet een geldige tekst zijn.',
            'name.max' => 'Naam mag niet langer zijn dan 255 tekens.',
            'user_id.required' => 'Selecteer een gebruiker.',
            'file.required' => 'Het is verplicht een contract te uploaden',
            'file.mimes' => 'Het bestand moet van het type PDF zijn.',
            'file.max' => 'Het bestand mag niet groter zijn dan 10MB'
            
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'file' => 'required|mimes:pdf|max:10240' // 10MB
        ], $messages);

        $path = $request->file('file')->store('contracts', 'public');

        Contract::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
            'file_path' => $path
        ]);

        return redirect()->route('upload.contract')->with('success', 'U heeft successvol een contract aangemaakt.');
    }

    //GET
    public function exportContract()
    {
       $users = User::where('role_id', 3)->get(); // zakelijke gebruikers 
       return view('contracts.export-registration', compact('users'));
    }

    //GET
    public function downloadContractPdf(User $user)
    {
        $pdf = FacadePdf::loadView('contracts.pdf-template', compact('user'));

        return $pdf->download('registratie_' . $user->name . '.pdf');
    }
}
