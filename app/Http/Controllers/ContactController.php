<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\Group;
use App\Invoice;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['invoices', 'info']]);
    }

    public function list(Request $request)
    {
        $query = null;
        if (($gid = $request->input('group_id'))) {
            $query = Group::findOrFail($gid)->contacts();
        } else {
            $query = Contact::limit(1000);
        }
        if (($s = $request->input('name'))) {
            $query->where('name', 'like', "%{$s}%");
        }
        if (($s = $request->input('email'))) {
            $query->where('email', 'like', "%{$s}%");
        }
        if (($s = $request->input('q'))) {
            $query->search($s);
        }
        return $query->paginate(10);
    }

    public function edit(Request $request, $id = null)
    {
        $contact = Contact::find($id);

        $data = [
            'name'  => $request->input('name'),
            'email' => $request->input('email'),
            'cf'    => strtoupper($request->input('cf')),
        ];
        validate(Contact::$rules, $data);

        if (!$contact || $data['email'] != $contact['email']) {
            if (Contact::whereEmail($data['email'])->exists()) {
                return error('La mail è già in uso da un altro contatto');
            }
        }

        if (!$contact || $data['cf'] != $contact['cf']) {
            if (Contact::whereCf($data['cf'])->exists()) {
                return error('Il codice fiscale è già in uso da un altro contatto');
            }
        }

        if ($contact) {
            $contact->update($data);
        } else {
            $contact = Contact::create($data);
        }

        return $contact;
    }

    public function delete($id)
    {
        return Contact::whereId($id)->delete();
    }

    public function addInvoice(Request $request)
    {
        \DB::beginTransaction();
        $file = \App\File::upload($request->file('file'));
        if (!$file) {
            return error(\App\File::getError());
        }

        $contact = Contact::whereCf($file->basename())->firstOrFail();
        $data = [
            'contact_id' => $contact->id,
            'file_id'    => $file->id,
        ];
        validate(Invoice::$rules, $data);
        $invoice = Invoice::create($data);
        $c = $invoice->contact; // Load eloquent relationship

        $file->update(['name' => "Fattura {$c->name} ".date('d/m/Y').".{$file->ext()}"]);
        \DB::commit();

        notify(
            $c->email, $c->name,
            'Nuova fattura',
            'Prema sul seguente bottone per visualizzare lo storico delle sue fatture',
            \URL::to("/invoices/{$c->token}")
        );
        return $invoice;
    }

    public function invoices($token)
    {
        return Contact::whereToken($token)
            ->firstOrFail()
            ->invoices()
            ->orderBy('id', 'desc')
            ->paginate(100);
    }

    public function info($token)
    {
        return Contact::whereToken($token)->firstOrFail();
    }
}
