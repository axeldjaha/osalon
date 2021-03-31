<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Exports\ExportSmsGroupe;
use App\Imports\ImportSmsGroupe;
use App\SmsGroupe;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SmsGroupeController extends Controller
{
    public function index()
    {
        $data['title'] = "Envoi SMS";
        $data['active'] = 'sms';

        $data['groupes'] = SmsGroupe::orderBy("intitule")->get();

        return view("sms.groupe.index", $data);
    }

    public function storeContact(Request $request)
    {
        $this->validate($request, [
            "nom" => "nullable",
            "telephone" => "required",
            "groupe" => "required|exists:sms_groupes,id",
        ]);

        $smsGroupe = SmsGroupe::find($request->groupe);
        if($smsGroupe == null)
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', "Le groupe n'existe pas ou a été supprimé.");
            return back();
        }

        if(!$smsGroupe->contacts()->where("telephone", $this->formatPhoneNumber($request->telephone))->exists())
        {
            Contact::create([
                "nom" => $request->nom,
                "telephone" => $request->telephone,
                "sms_groupe_id" => $request->groupe,
            ]);
        }

        session()->flash('type', 'alert-success');
        session()->flash('message', "Contact ajouté avec succès!");

        return redirect()->route("sms.fichier.show", $request->groupe);
    }

    public function formatPhoneNumber($telephone)
    {
        $phoneNumber = preg_replace('/[\-.\s]/s','', $telephone);
        $phoneNumber = substr($phoneNumber, 0, 10);
        return $phoneNumber;
    }

    public function show(SmsGroupe $smsGroupe)
    {
        $data['title'] = "Fichier de contact";
        $data['active'] = 'sms';

        $data['groupe'] = $smsGroupe;
        $data['groupes'] = SmsGroupe::orderBy("intitule")->get();
        $data['contacts'] = $smsGroupe->contacts()->orderBy("nom")->get();

        return view("sms.groupe.show", $data);
    }

    public function importer(Request $request)
    {
        $this->validate($request, [
            'intitule' => 'required',
            'fichier' => 'required|file|mimes:xls,xlsx',
        ]);

        $data = new ImportSmsGroupe();
        Excel::import($data, request()->file('fichier'));
        $rowCount = $data->getRowCount();
        if($rowCount > 0)
        {
            session()->flash('type', 'alert-success');
            session()->flash('message', "$rowCount contacts importés avec succès!");
        }
        else
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', "Aucun contact n'a été importé. Vérifiez que votre fichier contient les colonnes obligatoires");
            return back()->withInput();
        }

        return redirect()->route("sms.fichier.index");
    }

    public function exporter(SmsGroupe $smsGroupe)
    {
        if($smsGroupe->contacts->count() == 0)
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', "Aucun contact n'a été trouvé!");
            return back();
        }

        $data = new ExportSmsGroupe($smsGroupe->contacts()->orderBy("nom")->get());

        return Excel::download($data, $smsGroupe->intitule.".xlsx");
    }

    public function destroy(SmsGroupe $smsGroupe)
    {
        if(SmsGroupe::destroy($smsGroupe->id))
        {
            session()->flash('type', 'alert-success');
            session()->flash('message', "Suppression effectuée avec succès!");
        }

        return back();
    }

    public function destroyContact(Contact $contact)
    {
        if(Contact::destroy($contact->id))
        {
            session()->flash('type', 'alert-success');
            session()->flash('message', "Suppression effectuée avec succès!");
        }

        return back();
    }

}
