<?php

namespace App\Http\Controllers;


use App\Models\Users\DocumentVersion;
use App\Models\Users\User;
use App\Models\Users\UserLog;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\Users\UserDoc;
use Storage;

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
    /**
     * @var User
     */
    protected $users;

    public function __construct(UserDoc $documents, DocumentVersion $versions, User $users)
    {
        $this->users = $users;
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
            'document' => 'required|mimes:docx,pdf,png',
        ]);

        $document = UserDoc::create($data);
        $document->setUserId(\Auth::id());
        $document->save();

        /**
         * @var UploadedFile $image
         */
        $doc = $request->file('document');

        $filename = $request->file('document')->getClientOriginalName();

        $version = $request->only('version');
        $document->setFilename($filename);
        $document->save();

        $nextVersionId = $document->getNextVersionId();

        $filename = str_replace(['.',$doc->getClientOriginalExtension()],'',$filename);
        $filename .= '-'.$nextVersionId.'.'.$doc->getClientOriginalExtension();
        /**
         * @var FilesystemAdapter $storage
         */
        $storage = Storage::disk('public');


        if (isset($doc)) {
            $documentSource = $doc->get();

            /**
             * @var $localPath string
             *
             */
            $localPath = '/users/documents/';
            $storage->putFileAs($localPath, $doc, $filename);
            $publicPath = $storage->url($localPath.$filename);
            $document->setDocUrl($publicPath);
            $document->save();

        };

        /**
         * @var DocumentVersion $version
         */
        $data['version']  = $nextVersionId;
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
     * @param UserDoc $document
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function edit(UserDoc $document)
    {
        return \view('users.documents.edit',compact('document'));
    }


    public function versionsStore(UserDoc $document, Request $request){
        $data = $request->file('document_version');


        /**
         * @var $localPath string
         *
         */
        $nextVersionId  = $document->getNextVersionId();
        $filename = $data->getClientOriginalName();
        $extension = $data->getClientOriginalExtension();

        $filename = str_replace(['.',$extension],'',$filename);
        $filename = Str::slug($filename);
        $filename .= '-'.$nextVersionId.'.'.$extension;
        /**
         * @var FilesystemAdapter $storage
         */
        $storage = Storage::disk('public');
        $localPath = '/users/documents/';
        $storage->putFileAs($localPath, $data, $filename);
        $publicPath = $storage->url($localPath.$filename);

        $version = $document->versions()->create([
            'user_id'=>\Auth::id(),
            'filename'=>$data->getClientOriginalName(),
            'version'=>$document->getNextVersionId(),
            'doc_url'=>$publicPath,
        ]);
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
