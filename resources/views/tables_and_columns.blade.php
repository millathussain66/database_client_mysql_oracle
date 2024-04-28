@extends('layout_master')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tables and Columns</h1>

  <form class="row g-3" action="{{ route('tables.and.columns') }}" method="get">
    <div class="col-auto">
      <label for="table" class="fw-bold">Table</label>
      <input type="text" class="form-control" id="table" name="table" value="{{ $table }}">
    </div>
    <div class="col-auto">
      <label for="column" class="fw-bold">Column</label>
      <input type="text" class="form-control" id="column" name="column" value="{{ $column }}">
    </div>
    <div class="col-auto">
      <label for="comment" class="fw-bold">Comment</label>
      <input type="text" class="form-control" id="comment" name="comment" value="{{ $comment }}">
    </div>
    <div class="col-auto">
      <label class="fw-bold"> </label><br>
      <button type="submit" class="btn btn-primary">Search</button>
      <input type="hidden" name="action" value="search">
    </div>
  </form>
</div>


<div class="table-responsive small scrollable-table" style="width: 100%; height: 600px; font-size: 85% !important; overflow-x: auto; overflow-y: auto; ">
    <table class="table table-striped table-hover table-sm table-responsive small">
        <thead class="sticky-header">
            <tr>
                <th scope="col">SL</th>
                <th scope="col">Table</th>
                <th scope="col">Column</th>
                <th scope="col">Type</th>
                <th scope="col">Comment</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $key=>$row)
            <tr>
                <td>{{ $key+1 }}</td>
                <td><b>{{ $row->table_name }}</b></td>
                <td>{{ $row->column_name }}</td>
                <td>{{ $row->data_type }}</td>
                <td><i>{{ $row->column_comment }}</i></td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection