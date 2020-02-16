<?php

namespace App\Http\Controllers;


use App\Models\Users\User;
use App\Models\Users\UserLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Users\UserDoc;

class DocumentController extends Controller
{
    /**
     * @var UserDoc
     */
    protected $documents;


    public function __construct(UserDoc $documents)
    {
        $this->documents = $documents;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $frd = $request->all();
        $documents = $this->documents::filter($frd)->paginate(20);

        return view('users.documents.index', compact('documents', 'frd'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('users.documents.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->validate($request, [
            'name' => 'required|min:1|max:50',
            'document' => 'required',
        ]);
        $documents = UserDoc::create($data);
        $documents->setUserId(\Auth::id());
        $documents->save();
        return redirect()->route('documents.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
