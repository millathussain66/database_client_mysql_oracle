-- List Tables
SELECT TABLE_NAME, NUM_ROWS, PARTITIONED
FROM USER_TABLES;

-- List Columns of a table
SELECT COLUMN_NAME, DATA_TYPE, DATA_LENGTH, DATA_PRECISION, DATA_SCALE
FROM ALL_TAB_COLUMNS
WHERE TABLE_NAME = 'REF_THANA';

-- Show Create-TABLE Statement
SELECT DBMS_METADATA.GET_DDL('TABLE', 'REF_THANA', 'LOSCBL') AS CREATE_STATEMENT
FROM DUAL;


-- List SEQUENCE name and last value
SELECT SEQUENCE_NAME, LAST_NUMBER, INCREMENT_BY, MIN_VALUE, MAX_VALUE
FROM USER_SEQUENCES;

-- Show Create-SEQUENCE Statement
SELECT DBMS_METADATA.GET_DDL('SEQUENCE', 'SQ_AGENCY', 'LOSCBL') AS CREATE_STATEMENT
FROM DUAL;


-- List Triggers
SELECT TRIGGER_NAME, TRIGGER_TYPE, TRIGGERING_EVENT, TABLE_NAME, STATUS, TRIGGER_BODY
FROM USER_TRIGGERS;

-- Show Create-TRIGGER Statement
SELECT DBMS_METADATA.GET_DDL('TRIGGER', 'CPV_MASTER_ITEM_AUTO', 'LOSCBL') AS CREATE_STATEMENT
FROM DUAL;


/*
DBMS_METADATA.GET_DDL function in Oracle is used to retrieve the Data Definition Language (DDL) for a database object. This function allows you to extract the DDL statements used to create or alter various types of schema objects such as tables, indexes, views, procedures, functions, and more.

Basic syntax of the DBMS_METADATA.GET_DDL function:

DBMS_METADATA.GET_DDL(
    object_type IN VARCHAR2,
    name IN VARCHAR2,
    schema IN VARCHAR2 DEFAULT NULL,
    version IN VARCHAR2 DEFAULT 'COMPATIBLE',
    model IN VARCHAR2 DEFAULT 'ORACLE',
    transform IN VARCHAR2 DEFAULT 'DDL')
RETURN CLOB;

Here:
	object_type: The type of database object for which you want to retrieve the DDL (e.g., 'TABLE', 'SEQUENCE', 'TRIGGER', VIEW', 'INDEX', 'PROCEDURE', etc.).
	name: The name of the specific database object for which you want to retrieve the DDL.
	schema: The schema name of the object. If not specified, the current schema is assumed.
	version: The compatibility level of the DDL (default is 'COMPATIBLE').
	model: The metadata model to use (default is 'ORACLE').
	transform: The transformation option for the DDL (default is 'DDL').

The function returns a CLOB data type containing the DDL statement(s) for the specified object
*/