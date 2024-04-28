<?php //pa($fields);?>

@extends('layout_master')

@section('content')

      <h1 class="h2">Columns of <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">{{ $table }}</span> table</h1>

      <div class="table-responsive small">
        <table class="table table-striped table-hover table-sm table-responsive small">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Column Name</th>
              <th scope="col">Type</th>
              <th scope="col">Length</th>
              <th scope="col">Percision</th>
              <th scope="col">Scale</th>
              <th scope="col">Comment</th>
            </tr>
          </thead>
          <tbody>
            @foreach($fields as $field) 
            <tr>
              <td>{{ $loop->iteration }}.</td>
              <td class="fw-bold">{{ $field->column_name }}</td>
              <td>{{ $field->data_type }}</td>
              <td>{{ $field->data_length }}</td>
              <td>{{ $field->data_precision }}</td>
              <td>{{ $field->data_scale }}</td>
              <td>{{ $field->column_comment }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
@endsection