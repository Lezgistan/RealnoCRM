<?php


try {
    //Home
    Breadcrumbs::for('home', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail) {
        $trail->push('Дом', route('home'));
    });

//Users
    Breadcrumbs::for('users.index', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail) {
        $trail->parent('home');
        $trail->push('Пользователи', route('users.index'));
    });
    Breadcrumbs::for('users.create', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail) {
        $trail->parent('users.index');
        $trail->push('Создание пользователя', route('users.create'));
    });
    Breadcrumbs::for('users.show', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail, \App\Models\Users\User $user) {
        $trail->parent('users.index');
        $trail->push($user->getName(), route('users.show', $user));
    });
    Breadcrumbs::for('users.edit', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail, \App\Models\Users\User $user) {
        $trail->parent('users.show',$user);
        $trail->push('Редактирование пользователя', route('users.edit', $user));
    });

    Breadcrumbs::for('users.logs', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail, \App\Models\Users\User $user) {
        $trail->parent('users.edit', $user);
        $trail->push('Логи', route('users.logs', $user));
    });


    Breadcrumbs::for('users.password', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail, \App\Models\Users\User $user) {
        $trail->parent('users.edit', $user);
        $trail->push('Смена пароля', route('users.password', $user));
    });

    //Roles
    Breadcrumbs::for('roles.create', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail) {
        $trail->parent('roles.index');
        $trail->push('Создание ролей', route('roles.create'));
    });
    Breadcrumbs::for('roles.index', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail) {
        $trail->parent('home');
        $trail->push('Список ролей', route('roles.index'));
    });
    Breadcrumbs::for('roles.show', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail, \App\Models\Users\Role $role) {
        $trail->parent('roles.index',$role);
        $trail->push($role->getDisplayName(), route('roles.show', $role));
    });
    Breadcrumbs::for('roles.edit', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail, \App\Models\Users\Role $role) {
        $trail->parent('roles.show',$role);
        $trail->push('Редактирование роли', route('roles.edit', $role));
    });


    //Permissions
    Breadcrumbs::for('permissions.create', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail) {
        $trail->parent('home');
        $trail->push('Создание разрешений', route('permissions.create'));
    });
    Breadcrumbs::for('permissions.index', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail) {
        $trail->parent('home');
        $trail->push('Разрешения', route('permissions.index'));
    });
    Breadcrumbs::for('permissions.show', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail, \App\Models\Users\Permission $permission) {
        $trail->parent('permissions.index',$permission);
        $trail->push($permission->getDisplayName(), route('roles.show', $permission));
    });
    Breadcrumbs::for('permissions.edit', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail, \App\Models\Users\Permission $permission) {
        $trail->parent('permissions.show',$permission);
        $trail->push('Редактирование разрешения', route('roles.edit', $permission));
    });

    //Documents
    Breadcrumbs::for('documents.index', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail) {
        $trail->parent('home');
        $trail->push('Документы', route('documents.index'));
    });
    Breadcrumbs::for('documents.create', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail) {
        $trail->parent('documents.index');
        $trail->push('Загрузка документа', route('documents.create'));
    });

    Breadcrumbs::for('documents.show', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail, \App\Models\Users\UserDoc $document) {
        $trail->parent('documents.index');
        $trail->push('Документ №'.$document->getId().' «'.$document->getName().'»', route('documents.show', $document));
    });
    Breadcrumbs::for('documents.edit', function (\DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator $trail, \App\Models\Users\UserDoc $document) {
        $trail->parent('documents.show',$document);
        $trail->push('Новая версия', route('documents.edit', $document));
    });

} catch (\DaveJamesMiller\Breadcrumbs\Exceptions\DuplicateBreadcrumbException $e) {
    echo $e->getMessage();
}



