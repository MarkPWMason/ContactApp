<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactController extends Controller
{

    public function __construct(protected CompanyRepository $company)
    {

    }
    
    public function index(CompanyRepository $company, Request $request)
    {
        $companies = $company->pluck();
        //$contacts = Contact::latest()->paginate(10);
        $contactsCollection = Contact::latest()->get();

        $perPage = 10;
        $currentPage = request()->query('page', 1);
        $items = $contactsCollection->slice(($currentPage * $perPage) - $perPage,$perPage);
        $total = $contactsCollection->count();
        $contacts = new LengthAwarePaginator($items, $total, $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query()
        ]);
        
        return view('contacts.index', compact('contacts', 'companies'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function show(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        abort_if(!empty($contact), 404);
        return view('contacts.show')->with('contact', $contact);
    }
}
