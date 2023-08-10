<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Listing;

class ListingController extends Controller
{
    //show all listings
   public function index(){
     return view('listings.index',[
        'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
    ]);
   }

   //show single listing
   public function show(Listing $listing){
        return view('listings.show',[
            'listing' => $listing
        ]);
   }

   //show Create form
   public function create() {
    return view('listings.create');
   }


   //store listing data
   public function store(Request $request) {
    $formFields = $request->validate([
        'title' => 'required',
        'company' => ['required', Rule::unique('listings','company')],
        'location' => 'required',
        'website' => 'required',
        'email' => ['required', 'email'],
        'tags' => 'required',
        'description' => 'required'
    ]);

    if($request->hasFile('logo')) {
        $formFields['logo'] = $request->file('logo')->store('logos', 'public');
    }

    Listing::create($formFields);

    return redirect('/')->with('message', 'Listing created successfull!');
    
   }

      //Show Edit form
   public function edit(Listing $listing) {

    return view('listings.edit', ['listing' => $listing]);

   }

   //update listing data
   public function update(Request $request, Listing $listing) {
    $formFields = $request->validate([
        'title' => 'required',
        'company' => 'required',
        'location' => 'required',
        'website' => 'required',
        'email' => ['required', 'email'],
        'tags' => 'required',
        'description' => 'required'
    ]);

    if($request->hasFile('logo')) {
        $formFields['logo'] = $request->file('logo')->store('logos', 'public');
    }

    $listing->update($formFields);

    return back()->with('message', 'Listing updated successfull!');
    
   }

   public function destroy(Listing $listing){
        $listing->delete();

        return redirect('/')->with('message', 'Listing deleted successfull');
   }


}
