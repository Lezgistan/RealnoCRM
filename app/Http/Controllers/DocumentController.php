<?php

namespace App\Http\Controllers;


use App\Models\Users\DocumentVersion;
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
    /**
     * @var DocumentVersion
     */
    protected $versions;

    public function __construct(UserDoc $documents, DocumentVersion $versions)
    {
        $this->documents = $documents;
        $this->versions = $versions;
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

        $document = UserDoc::create($data);
        $document->setUserId(\Auth::id());
        $document->save();

        /**
         * @var DocumentVersion $version
         */
        $version = $document->versions()->create($data);
        $version->setUserId(\Auth::id());
        $version->save();
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

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function versions(Request $request,UserDoc $document)
    {
        $frd = $request->all();
        $versions = $this->versions->filterDocumentVersion($document->getId())->filter($frd)->paginate($frd['perPage'] ?? 20);
        return view('users.documents.versions', compact('versions'));
    }
}
