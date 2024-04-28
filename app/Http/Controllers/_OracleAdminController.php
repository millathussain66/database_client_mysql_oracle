<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\DdlService;


class OracleAdminController extends Controller
{
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

    public function tablesList()
    {
        // $sql = "SELECT TABLE_NAME, NVL(NUM_ROWS, 0) AS NUM_ROWS, PARTITIONED
        //     FROM USER_TABLES
        //     ORDER BY TABLE_NAME";

        $sql = "SELECT
                   TABLE_NAME,
                   TO_NUMBER(
                   EXTRACTVALUE(
                      XMLTYPE(
                         DBMS_XMLGEN.GETXML('SELECT COUNT(*) C FROM ' || TABLE_NAME))
                    ,'/ROWSET/ROW/C')) NUM_ROWS,
                   PARTITIONED
                FROM 
                   USER_TABLES
                ORDER BY 
                   TABLE_NAME";

        $data['result'] =  DB::select($sql);

        return view('table_list', $data);
    }

    public function tableData($table, $listtablecolumns=0, $columnoperator=0, $inputval=0, $inputval2=0, $page=1, DdlService $ddl)
    {
        $data['table'] = strtoupper($table);
        $page = (int)$page;
        $data['page'] = !empty($page) ? $page : 1;
        $data['is_table_exist'] = $ddl->isTableExist($data['table']);
        $data['info'] = [];

        $operator = array(
            '1' => '=',
            '2' => '>',
            '3' => '>=',
            '4' => '<',
            '5' => '<=',
            '6' => '!=',
            '7' => 'LIKE',
            '8' => 'LIKE %...%',
            '9' => 'NOT LIKE',
            '10' => 'NOT LIKE %...%',
            '11' => 'IN (...)',
            '12' => 'NOT IN (...)',
            '13' => 'BETWEEN',
            '14' => 'NOT BETWEEN',
            '15' => 'IS NULL',
            '16' => 'IS NOT NULL'
        );

        if($data['is_table_exist']){
            $data['info'] = $ddl->tableData($data['table'], $data['page'],$listtablecolumns,$columnoperator,$inputval,$inputval2);
            $data['listtablecolumns'] = $ddl->listTableColumns($data['table']);
            $data['operator'] = $operator;
        }
        return view('table_data', $data);
    }





    public function tableColumns($table, DdlService $ddl)
    {
        $table = strtoupper($table);

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
