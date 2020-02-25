<?php

namespace App\Http\Controllers;


use App\Models\Users\User;
use App\Models\Users\UserLog;
use App\Providers\DocumentCreated;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
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
    use SEOToolsTrait;
    /**
     * @var UserDoc
     */
    protected $documents;

    /**
     * @var User
     */
    protected $users;

    /**
     * @var File
     */
    protected $files;

    public function __construct(UserDoc $documents, User $users, File $files)
    {
        $this->users = $users;
        $this->documents = $documents;
        $this->files = $files;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $this->seo()->setTitle('Документы');
        $frd = $request->all();
        $documents = $this->documents::filter($frd)->orderByDesc('id')->paginate(20);

        return view('users.documents.index', compact('documents', 'frd'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $this->seo()->setTitle('Загрузка документа');
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

        $user=\Auth::getUser();
        event(new \App\Events\Auth\DocumentCreated($document,$user));

        return redirect()->route('documents.index');
    }

    /**
     * @param UserDoc $document
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function show(UserDoc $document)
    {
        $this->seo()->setTitle('Документ №'.$document->getId());
        return view('users.documents.show', compact('document'));
    }

    /**
     * @param UserDoc $document
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function edit(UserDoc $document)
    {
        $this->seo()->setTitle('Добавление версии документа №'.$document->getId());
        return \view('users.documents.edit', compact('document'));
    }

    /**
     * @param UserDoc $document
     * @param Request $request
     * @param File $documents
     * @return \Illuminate\Http\RedirectResponse
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


        event(new \App\Events\Auth\DocumentUpdated($document));

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
     * @param UserDoc $document
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(UserDoc $document)
    {
        $versions = $document->versions()->get()->getQueueableIds();
        File::destroy($versions);

        $document->delete();
        $user=\Auth::getUser();
        event(new \App\Events\Auth\DocumentDeleted($document,$user));
        $flashMessages = [['type' => 'success', 'text' => 'Документ «' . $document->getName() . '» удален']];
        return redirect()->back()->with(compact('flashMessages'));
    }

    /**
     * @param Request $request
     * @param UserDoc $document
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function versions(Request $request, UserDoc $document)
    {
        $this->seo()->setTitle('Версии документа №'.$document->getId());
        /**
         * @var $frd array
         */
        $frd = $request->all();

        $versions = $document->versions()->filter($frd)->orderByDesc('id')->paginate($frd['perPage'] ?? 25);

        return view('users.documents.versions', compact('versions', 'document', 'frd'));
    }

}
