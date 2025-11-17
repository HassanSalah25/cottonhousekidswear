<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Models\User;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_contacts'])->only('index');
        $this->middleware(['permission:view_contacts'])->only('show');
        $this->middleware(['permission:delete_contacts'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $sort_search = null;
        $search_column = null;
        $contacts = ContactUs::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $contacts = $contacts->where('first_name', 'like', '%'.$sort_search.'%')
                ->orWhere('last_name', 'like', '%'.$sort_search.'%')
                ->orWhere('email', 'like', '%'.$sort_search.'%');
        }
        if($request->has('search_column'))
        {
            $search_column = $request->search_column;
            $contacts->where('subject', 'like', '%'.$search_column.'%')
                ->orWhere('message', 'like', '%'.$search_column.'%')
                ->orWhere('status', 'like', '%'.$search_column.'%');
        }
        $contacts = $contacts->paginate(15);
        return view('backend.contacts.index', compact('contacts', 'sort_search','search_column'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactUs $contact)
    {
        //
        return view('backend.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactUs $contactUs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactUs $contactUs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactUs $contactUs)
    {
        //
    }

    public function change_status(ContactUs $contact)
    {
        $contact->status = $contact->status == 0 ? 1 : 0;
        $contact->save();
        flash(translate('Status updated successfully'))->success();
        return redirect()->back();

    }
}
