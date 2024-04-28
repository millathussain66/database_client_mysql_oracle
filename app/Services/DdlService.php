<?php

namespace App\Services;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
// use Illuminate\Support\Arr;
use Carbon\Carbon;


class DdlService
{
    protected $owner = null;
    protected $limit = 15;

    public function __construct()
    {
        $this->owner = strtoupper(env('DB_USERNAME'));
    }

    public function dashboard()
    {
        $sql = "(SELECT '1' AS ORDERS, 'TABLE' AS DDL_TYPE, COUNT(*) AS TOTAL
            FROM ALL_TABLES
            WHERE OWNER = '" . $this->owner . "')

            UNION ALL

            (SELECT '2' AS ORDERS, 'SEQUENCE' AS DDL_TYPE, COUNT(*) AS TOTAL
            FROM ALL_SEQUENCES
            WHERE SEQUENCE_OWNER = '" . $this->owner . "'
            AND SEQUENCE_NAME NOT LIKE '%$%')

            UNION ALL

            (SELECT '3' AS ORDERS, 'TRIGGER' AS DDL_TYPE, COUNT(*) AS TOTAL
            FROM ALL_TRIGGERS
            WHERE TABLE_OWNER = '" . $this->owner . "')

            ORDER BY ORDERS ASC";

        return DB::select($sql);
    }

    public function isTableExist($table)
    {
        $count = DB::table('USER_TABLES')
            ->where('TABLE_NAME', $table)
            ->count();

        return $count > 0 ? true : false;
    }

    public function listTables($include_row_count = 0)
    {
        if ($include_row_count == 0) {
            $sql = "SELECT TABLE_NAME
                FROM USER_TABLES
                ORDER BY TABLE_NAME";
        } else if ($include_row_count == 1) {
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
        }

        return DB::select($sql);
    }

    public function listTableColumns($table)
    {
        return DB::table('ALL_TAB_COLUMNS')
            ->select('COLUMN_NAME', 'DATA_TYPE', 'COLUMN_ID')
            ->where('TABLE_NAME', $table)
            ->where('OWNER', $this->owner)
            ->distinct()
            ->orderBy('COLUMN_ID', 'ASC')
            ->get()
            ->toArray();
    }

    public function listTablesAndColumns($table = '', $column = '', $comment = '', $action = '')
    {
        if (empty($action)) {
            return [];
        }

        $where1 = $where2 = '';
        $join_type = 'LEFT';

        if (!empty($table)) {
            $where1 .= " AND UPPER(A.TABLE_NAME) LIKE UPPER('%" . $table . "%')";
            $where2 .= " AND UPPER(TABLE_NAME) LIKE UPPER('%" . $table . "%')";
        }
        if (!empty($column)) {
            $where1 .= " AND UPPER(A.COLUMN_NAME) LIKE UPPER('%" . $column . "%')";
            $where2 .= " AND UPPER(COLUMN_NAME) LIKE UPPER('%" . $column . "%')";
        }
        if (!empty($comment)) {
            $where2 .= " AND UPPER(COMMENTS) LIKE UPPER('%" . $comment . "%')";
            $join_type = 'INNER';
        }



        $sql = "SELECT DISTINCT A.TABLE_NAME, A.COLUMN_NAME, A.COLUMN_ID, A.DATA_TYPE, C.COLUMN_COMMENT
        FROM ALL_TAB_COLUMNS A
        INNER JOIN USER_TABLES B ON (
            B.TABLE_NAME = A.TABLE_NAME
            $where1
        )
        $join_type JOIN (
            SELECT
                TABLE_NAME,
                COLUMN_NAME,
                COMMENTS AS COLUMN_COMMENT
            FROM ALL_COL_COMMENTS
            WHERE OWNER = '" . $this->owner . "' 
            $where2
        ) C ON C.TABLE_NAME = A.TABLE_NAME AND A.COLUMN_NAME = C.COLUMN_NAME
        WHERE A.OWNER = '" . $this->owner . "'
        ORDER BY A.TABLE_NAME, A.COLUMN_ID ASC";

        return DB::select($sql);



        // 4/22/2024 Query Command Date 
        // $sql = "SELECT DISTINCT A.TABLE_NAME, A.COLUMN_NAME, A.COLUMN_ID, A.DATA_TYPE, C.COLUMN_COMMENT
        //     FROM ALL_TAB_COLUMNS A
        //     INNER JOIN USER_TABLES B ON (
        //         B.TABLE_NAME = A.TABLE_NAME
        //         $where1
        //     )
        //     $join_type JOIN (
        //         SELECT
        //             TABLE_NAME,
        //             COLUMN_NAME,
        //             COMMENTS AS COLUMN_COMMENT
        //         FROM ALL_COL_COMMENTS
        //         WHERE OWNER = '$this->owner' 
        //         $where2
        //     ) C ON C.TABLE_NAME = A.TABLE_NAME AND A.COLUMN_NAME = C.COLUMN_NAME
        //     ORDER BY A.TABLE_NAME, A.COLUMN_ID ASC";
        // return DB::select($sql);






        // SELECT DISTINCT A.TABLE_NAME, A.COLUMN_NAME, A.COLUMN_ID, A.DATA_TYPE, C.COLUMN_COMMENT
        // FROM ALL_TAB_COLUMNS A
        // INNER JOIN USER_TABLES B ON (
        //         B.TABLE_NAME = A.TABLE_NAME
        //         AND UPPER(A.TABLE_NAME) LIKE UPPER('%HARD%')
        //         AND UPPER(A.COLUMN_NAME) LIKE UPPER('%STA%')
        // )
        // LEFT JOIN (
        //     SELECT
        //         TABLE_NAME,
        //         COLUMN_NAME,
        //         COMMENTS AS COLUMN_COMMENT
        //     FROM ALL_COL_COMMENTS
        //     WHERE OWNER = '$this->owner' 
        //     AND UPPER(TABLE_NAME) LIKE UPPER('%HARD%')
        //     AND UPPER(COLUMN_NAME) LIKE UPPER('%STA%')
        //     AND UPPER(COMMENTS) LIKE UPPER('%or%')
        // ) C ON C.TABLE_NAME = A.TABLE_NAME AND A.COLUMN_NAME = C.COLUMN_NAME
        // ORDER BY A.TABLE_NAME, A.COLUMN_ID ASC;
    }

    public function tableData($table, $page, $table_columns,$listtablecolumns,$tablecolumnattribute,$ColumnOperator8,$floatingTextarea1,$floatingTextarea2)
    {
        $data = [];

        try {
            $page = (int)$page;
            $page = $page > 0 ? ($page - 1) : 0;
            $offset = $page * $this->limit;
            $columns = array_map(function ($item) {
                return $item->column_name;
            }, $table_columns);

            $where = '';
            $query  = DB::table($table)->select($columns)->skip($offset)->take($this->limit);

            switch ($ColumnOperator8) {
        
                case "1":
                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= "UPPER( " . $listtablecolumns . " ) = '" . strtoupper($floatingTextarea1) . "'";
                    }else{
                        $where .= " " . $listtablecolumns . " = '" . $floatingTextarea1 . "'";
                    }
                    break;
                case "2":
                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= "UPPER( " . $listtablecolumns . " ) > '" . strtoupper($floatingTextarea1) . "'";
                    }else{
                        $where .= " " . $listtablecolumns . " > '" . $floatingTextarea1 . "'";
                    }
                    break;
                case "3":
                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= "UPPER( " . $listtablecolumns . " ) >= '" . strtoupper($floatingTextarea1) . "'";
                    }else{
                        $where .= " " . $listtablecolumns . " >= '" . $floatingTextarea1 . "'";
                    }
                    break;
                case "4":
                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= "UPPER( " . $listtablecolumns . " ) < '" . strtoupper($floatingTextarea1) . "'";
                    }else{
                        $where .= " " . $listtablecolumns . " < '" . $floatingTextarea1 . "'";
                    }
                    break;
                case "5":
                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= "UPPER( " . $listtablecolumns . " ) <= '" . strtoupper($floatingTextarea1) . "'";
                    }else{
                        $where .= " " . $listtablecolumns . " <= '" . $floatingTextarea1 . "'";
                    }
                    break;
                case "6":

                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= "UPPER(  " . $listtablecolumns . " ) != ''";
                    }else{
                        $where .= " " . $listtablecolumns . " != ''";
                    }

                    break;
                case "7":

                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= " UPPER(" . $listtablecolumns . ") LIKE '%" . strtoupper($floatingTextarea1) . "%'";
                    }else{
                        $where .= " " . $listtablecolumns . " LIKE '%" . $floatingTextarea1 . "%'";
                    }

                    break;
                case "8":

                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= " UPPER(" . $listtablecolumns . ") NOT LIKE '%" . strtoupper($floatingTextarea1) . "%'";
                    }else{
                        $where .= " " . $listtablecolumns . " NOT LIKE '%" . $floatingTextarea1 . "%'";
                    }

                    break;
                case "9":
                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= " UPPER(" . $listtablecolumns . ") IN (" .strtoupper($floatingTextarea1). ")";
                    }else{
                        $where .= " " . $listtablecolumns . " IN (" . $floatingTextarea1 . ")";
                    }

                    break;
                case "10":
                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= " UPPER(" . $listtablecolumns . ") NOT IN (" . strtoupper($floatingTextarea1). ")";
                    }else{
                        $where .= " " . $listtablecolumns . " NOT IN (" . $floatingTextarea1 . ")";
                    }
                    break;
                case "11":

                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= " UPPER(" . $listtablecolumns . ") BETWEEN '" . strtoupper($floatingTextarea1) . "' AND '" . strtoupper($floatingTextarea2) . "'";
                    }else{
                        $where .= " " . $listtablecolumns . " BETWEEN '" . $floatingTextarea1 . "' AND '" . $floatingTextarea2 . "'";
                    }

                    break;
                case "12":
                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= "  UPPER(" . $listtablecolumns . ") NOT BETWEEN '" . strtoupper($floatingTextarea1) . "' AND '" . strtoupper($floatingTextarea2) . "'";
                    }else{
                        $where .= " " . $listtablecolumns . " NOT BETWEEN '" . $floatingTextarea1 . "' AND '" . $floatingTextarea2 . "'";
                    }

                    break;
                case "13":
                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= " UPPER(" . $listtablecolumns . ") IS NULL ";
                    }else{
                        $where .= " " . $listtablecolumns . " IS NULL ";
                    }
                    break;
                case "14":
                    if($tablecolumnattribute=='NVARCHAR2' || $tablecolumnattribute=='VARCHAR2'){
                        $where .= " UPPER(" . $listtablecolumns . ") IS NOT NULL ";
                    }else{
                        $where .= " " . $listtablecolumns . " IS NOT NULL ";
                    }
                    break;
            }
            if(!empty($listtablecolumns)){
                $query->whereRaw($where);
            }
            $query2 = DB::table($table);

            if(!empty($listtablecolumns)){
                $query2->whereRaw($where);
            }
            $total_rows = $query2->count();
            $rows = $query->get();
            $data = [
                'total_rows' => $total_rows,
                'scale' => (int)ceil($total_rows / $this->limit),
                'columns' => $columns,
                'columns_lower' => array_to_lowercase($columns),
                'rows' => $rows,
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
        return $data;
    }




    public function isSequenceExist($sequence)
    {
        $count = DB::table('USER_SEQUENCES')
            ->where('SEQUENCE_NAME', $sequence)
            ->count();

        return $count > 0 ? true : false;
    }

    public function isTriggerExist($trigger)
    {
        $count = DB::table('USER_TRIGGERS')
            ->where('TRIGGER_NAME', $trigger)
            ->count();

        return $count > 0 ? true : false;
    }

    public function tableColumnsInfo($table)
    {
        $sql = "SELECT DISTINCT 
                A.COLUMN_NAME, 
                A.DATA_TYPE, 
                A.DATA_LENGTH, 
                A.DATA_PRECISION, 
                A.DATA_SCALE, 
                B.COLUMN_COMMENT, 
                A.COLUMN_ID
            FROM 
                ALL_TAB_COLUMNS A    
            LEFT JOIN (
                SELECT 
                    C.TABLE_NAME,
                    C.COLUMN_NAME,
                    C.COMMENTS AS COLUMN_COMMENT
                FROM 
                    ALL_COL_COMMENTS C
                JOIN 
                    ALL_TAB_COLUMNS TC ON C.OWNER = TC.OWNER 
                                       AND C.TABLE_NAME = TC.TABLE_NAME 
                                       AND C.COLUMN_NAME = TC.COLUMN_NAME
                WHERE 
                    C.TABLE_NAME = '" . $table . "'
            ) B ON B.TABLE_NAME = A.TABLE_NAME 
                 AND B.COLUMN_NAME = A.COLUMN_NAME
            WHERE 
                A.TABLE_NAME = '" . $table . "'
            ORDER BY 
                A.COLUMN_ID";
        return DB::select($sql);

        // return DB::table('ALL_TAB_COLUMNS')
        //     ->select('COLUMN_NAME', 'DATA_TYPE', 'DATA_LENGTH', 'DATA_PRECISION', 'DATA_SCALE')
        //     ->where('TABLE_NAME', $table)
        //     ->get();
    }

    public function getCreateStatement($param, $val)
    {
        switch ($param) {
            case "TABLE":
                $sql = "SELECT DBMS_METADATA.GET_DDL('$param', '$val', '$this->owner') AS STATEMENT
        			FROM DUAL";
                $data = DB::select($sql);
                break;
            case "SEQUENCE":
                $sql = "SELECT DBMS_METADATA.GET_DDL('$param', '$val', '$this->owner') AS STATEMENT
					FROM DUAL";
                $data = DB::select($sql);
                $data[0]->statement = preg_replace('/(CREATE|START|MAXVALUE|MINVALUE|INCREMENT|NOCYCLE|CACHE|NOORDER|NOKEEP|NOSCALE|GLOBAL)/', "\n\t$1", $data[0]->statement);
                $data[0]->statement = trim($data[0]->statement) . ';';
                break;
            case "TRIGGER":
                $sql = "SELECT DBMS_METADATA.GET_DDL('$param', '$val', '$this->owner') AS STATEMENT
        			FROM DUAL";
                $data = DB::select($sql);
                break;
            default:
                $data = null;
        }

        return $data;
    }
}
