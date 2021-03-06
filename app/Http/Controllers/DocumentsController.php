<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Document;
use App\Organisation;
Use App\Profile;
Use App\ProfileDocument;
use Session;
use Validator;
use Auth;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $documents= Document::all();
        
        return view ("document.index", [ 

            "documents" => $documents
         ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documents= Document::all();
        $organisations = Organisation::all();

       return view ("document.create", [ 
           "documents"=> $documents,
           "organisations" =>$organisations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $messages = [
            'name.required' => 'Dokumento pavadinimas tuščias',
            'text.required' => 'Dokumento tekstas tuščias',
            'age_from.required' => 'Pasirinkite amžiaus pradžią',
            'age_till.required' => 'Pasirinkite amžiaus pabaigą',
            'create_date.required' => 'Nenustatyta sukūrimo data',
            'valid_till.required' => 'Nenustatyta dokumento galiojimo data',
           
        ];
    // Patikriname uzklausos duomenis
        $validatedDocument = $request->validate([
            'name' => 'required',
            'text' => 'required',
            'age_from' => 'required',
            'age_till' => 'required',
            'create_date' => 'required',
            'valid_till' => 'required',
            'org_id' => 'required',
          
                        
        ], $messages);
    
        $document= new document;
    
        $document->name = $request ->name;
        $document->text = $request ->text;
        $document->age_from = $request ->age_from;
        $document->age_till = $request ->age_till;
        $document->create_date = $request->create_date;
        $document->valid_till =  $request->valid_till;
        $document->org_id = $request ->organisation_id;
        $document->price =  $request ->price;
        
        $document->save();
    
        Session::flash( 'status', 'Dokumentas sėkmingai išsaugotas' );
        return redirect()->route("document.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($document, $profile)
    {
        if(ProfileDocument::where([['profile_id',$profile],['document_id',$document]])->doesntExist())
            {
        $document= Document::find($document);
        $profile=Profile::find($profile);
        $result = 1;
            } else{
                $result = 0;
            }
        return view ("document.show", [ 
            "profile"=>$profile,
            "document" => $document,
            "result"=> $result,
         ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document= Document::find($id);
        $organisations = Organisation::all();

       return view ("document.edit", [ 
           "document"=> $document,
           "organisations"=>$organisations
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $messages = [
            'name.required' => 'Dokumento pavadinimas tuščias',
            'text.required' => 'Dokumento tekstas tuščias',
            'age_from.required' => 'Pasirinkite amžiaus pradžią',
            'age_till.required' => 'Pasirinkite amžiaus pabaigą',
            'create_date.required' => 'Nenustatyta sukūrimo data',
            'valid_till.required' => 'Nenustatyta dokumento galiojimo data',
           
        ];
    // Patikriname uzklausos duomenis
        $validatedDocument = $request->validate([
            'name' => 'required',
            'text' => 'required',
            'age_from' => 'required',
            'age_till' => 'required',
            'create_date' => 'required',
            'valid_till' => 'required',
            'org_id' => 'required',
          
                        
        ], $messages);
    
        $document= Document::find($id);
    
        $document->name = $request ->name;
        $document->text = $request ->text;
        $document->age_from = $request ->age_from;
        $document->age_till = $request ->age_till;
        $document->create_date = $request->create_date;
        $document->valid_till =  $request->valid_till;
        $document->org_id = $request ->organisation_id;
        $document->price =  $request ->price;
        
        $document->save();
    
        Session::flash( 'status', 'Dokumentas sėkmingai atnaujintas' );
        return redirect()->route("document.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $documents= Document::find($id);
        $documents->delete();

        // Susikuriu sesijos pranesima
		Session::flash( 'status', 'Dokumento šablonas sėkmingai ištrintas ' );
        return redirect()->back();

    }
    public function accept($document, $profile, $accept)
    {
        
        $profiledocument= new ProfileDocument;
        $profiledocument->profile_id = $profile;
        $profiledocument->document_id = $document;
        $profiledocument->paid = 0;
        $profiledocument->save();
            
           

        Session::flash( 'status', 'Dokumentas išsaugoti  ' );
        return redirect()->route("profile.documents", [ 
            "profile"=>$profile,
            "document" => $document,
            "profiledocument" =>$profiledocument,
         ]);

}}