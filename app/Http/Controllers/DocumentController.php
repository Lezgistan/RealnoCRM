<?php

namespace App\Http\Controllers;


use App\Models\Users\User;
use App\Models\Users\UserLog;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\Users\UserDoc;
use Storage;
use App\Models\Users\File;

class DocumentController extends Controller
{
    /**
     * @var UserDoc
     */
    protected $documents;

    /**
     * @var User
     */
    protected $users;

    public function __construct(UserDoc $documents, User $users)
    {
        $this->users = $users;
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
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Illuminate\Validation\ValidationException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:1|max:50',
            'document' => 'required|mimes:docx,pdf,doc,txt',
        ]);

        /**
         * @var array
         */
        $data = $request->all();

        /**
         * @var UserDoc $document
         */
        $document = UserDoc::create($data);
        $document->setUserId(\Auth::id());
        $document->save();

        /**
         * @var File $version
         */
        $version = $document->versions()->create();

        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = $request->file('document');
        $version->addUploadFile($uploadedFile, $document->getNextVersionName());

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
     * @param UserDoc $document
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function edit(UserDoc $document)
    {
        return \view('users.documents.edit', compact('document'));
    }

    /**
     * @param UserDoc $document
     * @param Request $request
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Illuminate\Validation\ValidationException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function versionsStore(UserDoc $document, Request $request)
    {
        $this->validate($request, [
            'document_version' => 'required|mimes:docx,pdf,doc,txt',
        ]);

        /**
         * @var File $version
         */
        $version = $document->versions()->create();

        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = $request->file('document_version');
        $version->addUploadFile($uploadedFile, $document->getNextVersionName());
        return redirect()->route('documents.index');
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
    public function versions(Request $request, UserDoc $document)
    {
        $frd = $request->all();
        $versions = $this->versions->filterDocumentVersion($document->getId())->filter($frd)->paginate($frd['perPage'] ?? 20);
        return view('users.documents.versions', compact('versions'));
    }
}
