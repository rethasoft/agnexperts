<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    InspectionController,
    ClientController,
    StatusController,
    TypeController,
    SettingController,
    EmployeController,
    RoleController,
    AgendaController,
    NoteController,
    AjaxController,
    ServiceController,
    BlogController,
    CouponController,
    EmployeEventController,
    InvoiceController,
    FileController,
    DocumentController,
    CombiDiscountController
};


Route::middleware('auth')->group(function () {
    // Route::get('/', [DashboardController::class, 'index'])->name('tenant.dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard.index');
    // Resources
    Route::resource('inspections', InspectionController::class)
        ->names([
            'index' => 'tenant.inspections.index',
            'create' => 'tenant.inspections.create',
            'store' => 'tenant.inspections.store',
            'show' => 'tenant.inspections.show',
            'edit' => 'tenant.inspections.edit',
            'update' => 'tenant.inspections.update',
            'destroy' => 'tenant.inspections.destroy'
        ]);
    Route::get('inspections/send-invoice/{id}', [InspectionController::class, 'sendInvoice'])->name('inspections.send-invoice');


    Route::resource('client', ClientController::class)->names('client');
    Route::resource('status', StatusController::class)->names('status');
    Route::resource('dienst', TypeController::class)->names('dienst');
    Route::resource('setting', SettingController::class)->names('setting');
    Route::resource('employee', EmployeController::class)->names('employee');
    Route::resource('role', RoleController::class)->names('role');
    Route::resource('service', ServiceController::class)->names('service');
    Route::resource('blog', BlogController::class)->names('blog');
    Route::resource('coupon', CouponController::class)->names('coupon');
    Route::resource('agenda', AgendaController::class)->names('agenda');
    Route::resource('note', NoteController::class)->names('note');
    Route::resource('combi_discount', CombiDiscountController::class)->names('combi_discount');

    // Route::get('documents/download/{id}', [DocumentController::class, 'download']);
    // Sonra özel route'ları tanımlayın
    Route::get('documents/download/{id}', [DocumentController::class, 'download'])->name('documents.download');
    Route::post('documents/{modelType}/{modelId}', [DocumentController::class, 'store'])->name('documents.upload');

    // Document specific routes
    // Önce resource route'u tanımlayın, except parametresi ile özel route'ları hariç tutun
    Route::resource('documents', DocumentController::class)
        ->except(['download']) // store metodunu hariç tutuyoruz çünkü özel bir store route'umuz var
        ->names('documents');



    // Additional routes
    Route::get('setting/delete-logo/{id}', [SettingController::class, 'destroyLogo'])->name('setting.deleteLogo');
    // Route::resource('keuringen-detail', KeuringenDetailController::class);
    Route::resource('employe-event', EmployeEventController::class)->names([
        'store' => 'tenant.employe-event.store',
        'update' => 'tenant.employe-event.update',
        'destroy' => 'tenant.employe-event.destroy'
    ]);

    // Ajax routes
    Route::prefix('ajax')->group(function () {
        Route::get('/getTypesByClient/{id?}', [AjaxController::class, 'getTypesByClient']);
        Route::get('/getSales/{interval?}', [AjaxController::class, 'getSales']);
        Route::get('/getSalesByPartner/{interval?}', [AjaxController::class, 'getSalesByPartner']);
        Route::get('/getEmployes', [AjaxController::class, 'getEmployes']);
        Route::get('/getEvents', [AjaxController::class, 'getEvents']);
        Route::post('/updateEvent', [AjaxController::class, 'updateEvent']);
        Route::post('/deleteEvent', [AjaxController::class, 'deleteEvent']);
        Route::post('/assignInspection', [AjaxController::class, 'assignInspection']);
        Route::get('/deleteFile/{id}', [AjaxController::class, 'deleteFile'])->name('delete.file');
        Route::get('/sendFile/{id}', [AjaxController::class, 'sendFile'])->name('send.file');
        Route::get('/deleteType/{id}/{type_id}', [AjaxController::class, 'deleteType'])->name('delete.type');
    });

    // Invoice routes
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('update-status/{invoice}', [InvoiceController::class, 'updateStatus'])
            ->name('update-status');
    });

    // Secure File Display Routes
    Route::get('files/{type}/{filename}', [FileController::class, 'show'])
        ->name('files.show')
        ->where('type', 'invoices|documents')
        ->middleware('signed');
});
