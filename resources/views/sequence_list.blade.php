<?php //pad($result);?>

@extends('layout_master')

@section('content')
      <h1 class="h2">List of Sequences</h1>

      <div class="table-responsive small">
        <table class="table table-striped table-hover table-sm table-responsive small">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Sequence Name</th>
              <th scope="col">Schema</th>
              <th scope="col">Last Number</th>
              <th scope="col">Increment By</th>
              <th scope="col">Min Val</th>
              <th scope="col">Max Val</th>
            </tr>
          </thead>
          <tbody>
          	@foreach($result as $res)
            <tr>
              <td>{{ $loop->iteration }}.</td>
              <td>{{ $res->sequence_name }}</td>
              <td><a href="<?php echo route('sequence.schema', ['param' => $res->sequence_name]);?>">Schema</a></td>
              <td>{{ $res->last_number }}</td>
              <td>{{ $res->increment_by }}</td>
              <td>{{ $res->min_value }}</td>
              <td>{{ $res->max_value }}</td>
            </tr>
			      @endforeach
          </tbody>
        </table>
      </div>
@endsection