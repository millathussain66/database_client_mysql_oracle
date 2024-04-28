<?php //pa($fields);?>

@extends('layout_master')

@section('content')
      <h1 class="h2">List of Tables</h1>

      <div class="table-responsive small">
        <table class="table table-striped table-hover table-sm table-responsive small">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Table Name</th>
              <th scope="col">Rows</th>
              <th scope="col">Columns</th>
              <th scope="col">Schema</th>
              <th scope="col">Is Partitioned?</th>
            </tr>
          </thead>
          <tbody>
          	@foreach($result as $res)

            
            <tr>
              <td>{{ $loop->iteration }}.</td>
              <td class="fw-bold">{{ $res->table_name }}</td>                
              <td><a href="<?php echo route('table.data', ['param'=>$res->table_name, 'page'=>1 , 'listtablecolumns'=>0]);?>">{{ $res->num_rows }}</a></td>
              <td><a href="<?php echo route('table.columns', ['param' => $res->table_name]);?>">Columns</a></td>
              <td><a href="<?php echo route('table.schema', ['param' => $res->table_name]);?>">Schema</a></td>
              <td>{{ $res->partitioned }}</td>
            </tr>




			      @endforeach
          </tbody>
        </table>
      </div>
@endsection
