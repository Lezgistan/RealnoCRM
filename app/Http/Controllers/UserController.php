<?php

namespace App\Http\Controllers;

use App\Events\Auth\ChangePassword;
use App\Events\Auth\UserUpdate;
use App\Models\Users\User;
use App\Models\Users\Role;
use App\Models\Users\UserDoc;
use App\Models\Users\UserLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use App\Models\Users\Image;
use App\Models\Users\File;

class UserController extends Controller
{
    use SEOToolsTrait;
    /**
     * @var User
     */
    protected $users;
    /**
     * @var Role
     */
    protected $roles;

    /**
     * @var $userLogs
     */
    protected $userLogs;
    /**
     * @var UserDoc
     */
    protected $userDocs;

    /**
     * UserController constructor.
     * @param User $users
     * @param Role $roles
     * @param UserLog $userLogs
     */
    public function __construct(User $users, Role $roles, UserLog $userLogs, UserDoc $userDocs)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->userLogs = $userLogs;
        $this->userDocs = $userDocs;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {

        $this->seo()->setTitle('Пользователи');
        $frd = $request->all();

        $users = $this->users::filter($frd)->paginate(10);

        return view('users.index', compact('users', 'frd'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create(Request $request)
    {
        $this->seo()->setTitle('Добавление пользователя');
        $frd = $request->all();
        $roles = $this->roles::get();
        return view('users.create', compact('frd', 'roles'));
    }

    public function store(Request $request)
    {

        $dateTo = Carbon::now()->subYears(18);
        $this->validate($request, [
            'f_name' => 'required|min:1|max:50',
            'l_name' => 'required|min:1|max:50',
            'm_name' => 'required|min:1|max:50',
            'email' => 'required|unique:users',
            'image' => 'mimes:jpeg,jpg,png|dimensions:max_height=500,max_width=500,ratio=1/1',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'age' => ['required', 'before:' . $dateTo],
        ], [
            'age.before' => 'Вам должно быть больше 18',
            'image.dimensions' => 'Слишком маленькая картинка'
        ]);

        $data = $request->all();

        $user = User::create($data);
        $user->setName($data['f_name'], 1);
        $user->setName($data['m_name'], 2);
        $user->setName($data['l_name'], 0);
        $user->setPassword($data['password']);
        $user->save();

        /**
         * @var Image $avatar
         */
        $avatar = $user->images()->create();

        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = $request->file('image');
        $avatar->addImageFromUploadFile($uploadedFile,$user-> getNextAvatarName());




        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }


        $flashMessages = [['type' => 'success', 'text' => 'Пользователь «' . $user->getName() . '» создан']];
        event(new Registered($user));
        return redirect()->route('users.index')->with(compact('flashMessages'));

    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Factory|View
     */
    public function show(User $user)
    {
        $roles = $this->roles->all();
        return view('users.show', compact('user', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @param User $user
     * @return Factory|View
     */
    public function edit(User $user)
    {
        $this->seo()->setTitle('Управление — ' . $user->getName());
        return view('users.edit', compact('user'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'email' => 'required',
            'f_name' => 'required',
            'l_name' => 'required',
            'm_name' => 'required',
            'image' => 'mimes:jpeg,jpg,png|dimensions:max_height=500,max_width=500,ratio=1/1',
        ]);

        $frd = $request->all();

        /**
         * @var User $user
         */
        $user->setFirstName($frd['f_name']);
        $user->setLastName($frd['l_name']);
        $user->setMiddleName($frd['m_name']);
        $user->setEmail($frd['email']);
        $user->save();

        /**
         * @var Image $avatar
         */
        $avatar = $user->images()->create();

        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = $request->file('image');
        $avatar->addImageFromUploadFile($uploadedFile,$user-> getNextAvatarName());

        $flashMessages = [['type' => 'success', 'text' => 'Пользователь «' . $user->getName() . '» сохранен']];
        event(new UserUpdate($user));
        return redirect()->back()->with(compact('flashMessages'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $storage = Storage::disk('public');
        $oldAvatarLocalPath = $user->getImageUrlLocalPath();
        if (null !== $oldAvatarLocalPath && $storage->has($oldAvatarLocalPath)) {
            $storage->delete($oldAvatarLocalPath);
        }
        $user->delete();
        $flashMessages = [['type' => 'success', 'text' => 'Пользователь «' . $user->getName() . '» удален']];
        return redirect()->back()->with(compact('flashMessages'));
    }

    /**
     * @param User $user
     * @return Factory|View
     */
    public function roles(User $user)
    {
        $this->seo()->setTitle('Роли — ' . $user->getName());
        $roles = $this->roles::get();
        return view('users.roles', compact('user', 'roles'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rolesUpdate(Request $request, User $user)
    {
        $frd = $request->only('roles');
        $user->roles()->sync($frd['roles']);

        $flashMessages = [['type' => 'success', 'text' => 'Роли пользователя «' . $user->getName() . '» обновлены']];

        return redirect()->back()->with(compact('flashMessages'));
    }

    /**
     * @param Request $request
     * @param UserDoc $documents
     * @param User $user
     * @return Factory|View
     */
    public function documents(Request $request, UserDoc $documents, User $user)
    {
        $frd = $request->all();
        $documents = $this->userDocs->filterDocument($user->getKey())->filter($frd)->paginate($frd['perPage'] ?? 20);
        return view('users.documents', compact('documents'));
    }

    /**
     * @param User $user
     * @return Factory|View
     */
    public function password(User $user)
    {
        $this->seo()->setTitle('Пароль — ' . $user->getName());
        return view('users.password', compact('user'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function passwordUpdate(Request $request, User $user)
    {
        $frd = $request->all();
        $this->validate($request, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user->setPassword($frd['password']);
        $user->save();
        $flashMessages = [['type' => 'success', 'text' => 'Пароль пользователя «' . $user->getName() . '» обновлен']];
        event(new ChangePassword($user));
        return redirect()->back()->with(compact('flashMessages'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Factory|View
     */
    public function logs(Request $request, User $user)
    {
        $this->seo()->setTitle('Логи — ' . $user->getName());
        $frd = $request->all();
        $logs = $this->userLogs->filterUser($user->getKey())->filter($frd)->paginate($frd['perPage'] ?? 20);

        return view('users.logs', compact('user', 'logs'));
    }
}
