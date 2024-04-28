<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\DdlService;
use Symfony\Component\Console\Input\Input;

class OracleAdminController extends Controller
{
    protected $operator;
    public function __construct()
	{
        // Get Operator List From In Config Folder system.php
        $this->operator = config('system.operator');
    }

    public function blank()
    {
        return view('blank');
    }

    public function customQuery()
    {
        return view('query');
    }

    public function dashboard(DdlService $ddl)
    {
        $data['result'] = $ddl->dashboard();
        return view('dashboard', $data);
    }

    public function tablesList(DdlService $ddl)
    {
        $data['result'] =  $ddl->listTables(1);

        return view('table_list', $data);
    }

    public function tableData($table, $page=1, DdlService $ddl,Request $request)
    {


        $listtablecolumns     =  trim($request->get('listtablecolumns'));
        $tablecolumnattribute =  trim($request->get('tablecolumnattribute')) ;
        $ColumnOperator8      =  trim($request->get('ColumnOperator8'))  ;
        $floatingTextarea1    =  trim($request->get('floatingTextarea1'))  ;
        $floatingTextarea2    =  trim($request->get('floatingTextarea2')) ;

        


        $data['table'] = strtoupper($table);
        $page = (int)$page;
        $data['page'] = !empty($page) ? $page : 1;
        $data['is_table_exist'] = $ddl->isTableExist($data['table']);
        $data['info'] = [];

        if($data['is_table_exist']){
            $data['table_columns'] = $ddl->listTableColumns($data['table']);
            $data['info'] = $ddl->tableData($data['table'], $data['page'], $data['table_columns'],$listtablecolumns,$tablecolumnattribute,$ColumnOperator8,$floatingTextarea1,$floatingTextarea2);
            $data['operator'] = $this->operator;
            $data['table_name'] = $data['table'];
        } 

        $data['listtablecolumns'] = $listtablecolumns;
        $data['tablecolumnattribute'] = $tablecolumnattribute;
        $data['ColumnOperator8'] = $ColumnOperator8;
        $data['floatingTextarea1'] = $floatingTextarea1;
        $data['floatingTextarea2'] = $floatingTextarea2;
        return view('table_data', $data);
    }


    public function tablesAndColumns(Request $request, DdlService $ddl)
    {
        $data['table'] = strtoupper(trim($request->get('table')));
        $data['column'] = strtoupper(trim($request->get('column')));
        $data['comment'] = trim($request->get('comment'));
        $data['action'] = trim($request->get('action'));
        $data['result'] = $ddl->listTablesAndColumns($data['table'], $data['column'], $data['comment'], $data['action']);

        return view('tables_and_columns', $data);
    }

    public function tableColumns($table, DdlService $ddl)
    {
        $data['table'] = strtoupper($table);
        $data['is_table_exist'] = $ddl->isTableExist($data['table']);
        $data['fields'] = [];

        if($data['is_table_exist']){
            $data['fields'] = $ddl->tableColumnsInfo($data['table']);
        }

        // pad($data['fields']);

        return view('table_columns', $data);
    }

    public function tableSchema($table, DdlService $ddl)
    {
        $data['table'] = strtoupper($table);
        $data['is_table_exist'] = $ddl->isTableExist($data['table']);
        $data['statement'] = '';

        if($data['is_table_exist']){
            $data['statement'] = $ddl->getCreateStatement('TABLE', $data['table']);
        }

        return view('table_schema', $data);
    }

    public function sequencesList()
    {
        $sql = "SELECT SEQUENCE_NAME, LAST_NUMBER, INCREMENT_BY, MIN_VALUE, MAX_VALUE
                FROM USER_SEQUENCES
                WHERE SEQUENCE_NAME NOT LIKE '%$%'
                ORDER BY SEQUENCE_NAME ASC";

        $data['result'] =  DB::select($sql);

        return view('sequence_list', $data);
    }

    public function sequenceSchema($sequence, DdlService $ddl)
    {
        $data['sequence'] = strtoupper($sequence);
        $data['is_sequence_exist'] = $ddl->isSequenceExist($data['sequence']);
        $data['statement'] = '';

        if($data['is_sequence_exist']){
            $data['statement'] = $ddl->getCreateStatement('SEQUENCE', $data['sequence']);
        }

        return view('sequence_schema', $data);
    }

    public function triggersList()
    {
        $sql = "SELECT TRIGGER_NAME, TRIGGER_TYPE, TRIGGERING_EVENT, TABLE_NAME, STATUS, TRIGGER_BODY
                FROM USER_TRIGGERS
            ORDER BY TRIGGER_NAME ASC";

        $data['result'] =  DB::select($sql);

        return view('trigger_list', $data);
    }

    public function triggerSchema($trigger, DdlService $ddl)
    {
        $data['trigger'] = strtoupper($trigger);
        $data['is_sequence_exist'] = $ddl->isTriggerExist($data['trigger']);
        $data['statement'] = '';

        if($data['is_sequence_exist']){
            $data['statement'] = $ddl->getCreateStatement('TRIGGER', $data['trigger']);
        }

        return view('trigger_schema', $data);
    }

}
