<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\View\ViewDispatchController;
use App\Http\Controllers\Admin\Settings\SettingsController;
use App\Http\Controllers\Admin\ModulesController;
use App\Modules\Personnels\Controllers\RolePermissionController;


Route::prefix('/personnels')->name('personnels.')->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix("/settings")->name("settings.")->group(function () {
        Route::get("/", [SettingsController::class, 'index'])->name("index");
        Route::get("/{setting}", [SettingsController::class, 'edit'])->name("edit");
        Route::post("/{setting}", [SettingsController::class, 'store'])->name("store");

    });

    Route::prefix('/modules')->name('modules.')->middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {

        Route::get('/{module}/active', [ModulesController::class, 'active'])->name('active');
        Route::get('/{module}/inactive', [ModulesController::class, 'inactive'])->name('inactive');
        Route::post('/{module}/toggle', [ModulesController::class, 'toggleStatus'])->name('toggle');
        Route::get('/{module}/info', [ModulesController::class, 'getModuleInfo'])->name('info');

        Route::get('/', [ModulesController::class, 'index'])->name('index');
    });

    Route::prefix('/roles-permissions')->name('roles-permissions.')->group(function () {
        Route::get('/', [RolePermissionController::class, 'index'])->name('index');
        Route::get('/create-role', [RolePermissionController::class, 'createRole'])->name('create-role');
        Route::post('/create-role', [RolePermissionController::class, 'storeRole'])->name('store-role');
        Route::get('/edit-role/{role}', [RolePermissionController::class, 'editRole'])->name('edit-role');
        Route::put('/edit-role/{role}', [RolePermissionController::class, 'updateRole'])->name('update-role');
        Route::delete('/delete-role/{role}', [RolePermissionController::class, 'deleteRole'])->name('delete-role');
        Route::get('/user-roles/{user}', [RolePermissionController::class, 'getUserRoles'])->name('user-roles');
        Route::post('/assign-user-role/{user}', [RolePermissionController::class, 'assignUserRole'])->name('assign-user-role');
        Route::post('/assign-user-permission/{user}', [RolePermissionController::class, 'assignUserPermission'])->name('assign-user-permission');
    });

    Route::get("/", [ViewDispatchController::class, 'index'])->name("index");

});

// Inclure les routes des modules admin
if (file_exists(__DIR__ . '/../../app/Modules/Pages/Routes/admin.php')) {
    require __DIR__ . '/../../app/Modules/Pages/Routes/admin.php';
}





