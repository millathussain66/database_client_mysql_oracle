<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\OracleAdminController AS OA;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('', [OA::class, 'dashboard'])->name('dashboard');
Route::get('/query', [OA::class, 'customQuery'])->name('query');
Route::get('/blank', [OA::class, 'blank'])->name('blank');

Route::get('/tables', [OA::class, 'tablesList'])->name('table.list');

Route::get('/table/data/{param}/{page?}/{listtablecolumns?}', [OA::class, 'tableData'])->where('page', '[0-9]+')->name('table.data');


// Route::get('/table/data/{param}', [OA::class, 'TableData'])->where('param', '[a-zA-Z0-9_]+')->name('table.data');
// Route::get('/table/data/{param}/{page?}', [OA::class, 'tableData'])->name('table.data');



Route::get('/table/columns/{param}', [OA::class, 'tableColumns'])->name('table.columns');

# http://127.0.0.1/orca/tables-and-columns?table=hard&column=&comment=
Route::get('/tables-and-columns/', [OA::class, 'tablesAndColumns'])->name('tables.and.columns');

Route::get('/table/schema/{param}', [OA::class, 'tableSchema'])->name('table.schema');

Route::get('/sequences', [OA::class, 'sequencesList'])->name('sequence.list');
Route::get('/sequence/schema/{param}', [OA::class, 'sequenceSchema'])->name('sequence.schema');
Route::get('/triggers', [OA::class, 'triggersList'])->name('trigger.list');
Route::get('/trigger/schema/{param}', [OA::class, 'triggerSchema'])->name('trigger.schema');



Route::get('/phpinfo', function () {
    phpinfo();
});

Route::get('/db-query', function () {
    $result = DB::table('LEAD_STATUS')->select('*')->get();
    pa($result);
});




//  For Searching


Route::post('/table/tableData_search/',[OA::class,'tableData_search']);




