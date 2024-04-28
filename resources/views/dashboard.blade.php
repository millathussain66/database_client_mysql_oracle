@extends('layout_master')

@section('content')
      <!-- <h1 class="h2">Dashboard</h1> -->

      <div class="row row-cols-3"> <!-- table-responsive small -->
        {{-- <!-- <table class="table table-striped table-hover table-sm table-responsive small">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Category</th>
              <th scope="col">Count</th>
            </tr>
          </thead>
          <tbody>
          	@foreach($result as $res)
            <tr>
              <td class="fw-bold">{{ $loop->iteration }}.</td>
              <td class="fw-bold"><a href="<?php echo route(strtolower($res->ddl_type).'.list');?>"><span class="badge rounded-pill bg-secondary">{{ $res->ddl_type }}</span></a></td>
              <td class="fw-bold">{{ $res->total }}</td>
			      @endforeach
          </tbody>
        </table> --> --}}

        @foreach($result as $res)
        <div class="col-md-4" style="padding-top: 20px; padding-bottom: 0px;">
          <div class="h-90 p-5 bg-body-tertiary border rounded-3">
            <h2>{{ $res->ddl_type }}</h2>
            <p>Total records: <span class="badge rounded-pill bg-secondary">{{ $res->total }}</span></p>
            <a href="<?php echo route(strtolower($res->ddl_type).'.list');?>">
              <button class="btn btn-outline-secondary" type="button">Details</button>
            </a>
          </div>
        </div>
        @endforeach

      </div>
@endsection

