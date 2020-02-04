<?php

namespace App\Http\Controllers;
use App\Models\Users\Permission;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use SEOToolsTrait;
    /**
     * @var Permission
     */
    protected $permission;

    /**
     * PermissionController constructor.
     * @param Permission $permission
     */
    public  function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->seo()->setTitle('Разрешения');
        $frd = $request->all();
        $permissions = $this->permission::filter($frd)->paginate(10);


        return view('permissions.index', compact('permissions', 'frd'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $this->seo()->setTitle('Добавление разрешения');
        return view('permissions.create');
    }

    /**
     * @param Request $request
     * @param Permission $permission
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Permission $permission)
    {
        $frd = $request->all();


        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',

        ]);

        $data = $request->all();
        $permission = Permission::create($data);
        $permission->setName($data['name']);
        $permission->setDisplayName($data['display_name']);
        $permission->setDescription($data['description']);
        $permission->save();

        return redirect()->route('permissions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param Permission $permission
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Permission $permission)
    {
        $this->seo()->setTitle('Разрешение '.$permission->getDisplayName());
        return view('permissions.edit', compact('permission'));

    }

    /**
     * @param Request $request
     * @param Permission $permission
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Permission $permission)
    {
        $frd = $request->all();


        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',

        ]);
        /**
         * @var $permission $permission
         */
        $permission->setName($frd['name']);
        $permission->setDisplayName($frd['display_name']);
        $permission->setDescription($frd['description']);
        $permission->save();

        return redirect()->back();
    }

    /**
     * @param Permission $permission
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->back();
    }
}
