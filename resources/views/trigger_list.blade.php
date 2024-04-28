<?php //pad($result);?>

@extends('layout_master')

@section('content')
      <h1 class="h2">List of Triggers</h1>

      <div class="table-responsive small">
        <table class="table table-striped table-hover table-sm table-responsive small">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Trigger Name</th>
              <th scope="col">Schema</th>
              <th scope="col">Table Name</th>
              <th scope="col">Trigger Type</th>
              <th scope="col">Triggering Event</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
          	@foreach($result as $res)
            <tr>
              <td>{{ $loop->iteration }}.</td>
              <td>{{ $res->trigger_name }}</td>
              <td><a href="<?php echo route('trigger.schema', ['param' => $res->trigger_name]);?>">Schema</a></td>
              <td>{{ $res->table_name }}</td>
              <td>{{ $res->trigger_type }}</td>
              <td>{{ $res->triggering_event }}</td>
              <td>{{ $res->status }}</td>
            </tr>
			      @endforeach
          </tbody>
        </table>
      </div>
@endsection