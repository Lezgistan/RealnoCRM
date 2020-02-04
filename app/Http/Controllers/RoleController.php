<?php

namespace App\Http\Controllers;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;

class RoleController extends Controller
{
    use SEOToolsTrait;
    /**
     * @var Role
     */
    protected $roles;

    /**
     * UserController constructor.
     * @param Role $roles
     */
    public function __construct(Role $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->seo()->setTitle('Роли');
            $frd = $request->all();
            $roles = $this->roles::filter($frd)->paginate(20);

            return view('roles.index', compact('roles', 'frd'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->seo()->setTitle('Добавление роли');
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->validate($request, [
            'name' => 'required|min:1|max:50|unique:roles',
            'display_name' => 'required|min:1|max:50|unique:roles',

        ]);

        $role = Role::create($data);
        $role->save();
        return redirect()->route('roles.index');
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
     * Show the form for editing the specified resource.
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $this->seo()->setTitle('Роль '.$role->getDisplayName());
        return view('roles.edit', compact('role'));
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Role $role)
    {
        $frd = $request->all();

        $this->validate($request, [
            'name' => 'required|min:1|max:50|unique:roles,name,'.$role->getKey(),
            'display_name' => 'required|min:1|max:50|unique:roles,display_name,'.$role->getKey(),
        ]);

        /**
         * @var Role $role
         */
        $role->setName($frd['name']);
        $role->setDisplayName($frd['display_name']);
        $role->setDescription($frd['description']);
        $role->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index');
    }
}
