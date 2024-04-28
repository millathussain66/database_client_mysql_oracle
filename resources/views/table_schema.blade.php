<?php //pad($result);?>

@extends('layout_master')

@section('content')
      <h1 class="h2">Schema of <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">{{ $table }}</span> Table</h1>

      <div class="table-responsive small">
        {{ nice_echo($statement[0]->statement) }}
      </div>
@endsection