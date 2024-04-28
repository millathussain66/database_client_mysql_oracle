@extends('layout_master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

        <h1 class="h2">Data of <span
                class="badge bg-primary-subtle text-primary-emphasis rounded-pill">{{ $table }}</span> table</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1"
                    type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg class="bi">
                        <use xlink:href="#calendar3" />
                    </svg>
                    Page {{ $page }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="max-height: 300px; overflow-y: auto;">
                    @for ($i = 1; $i <= $info['scale']; $i++)
                        <li><a class="dropdown-item" href="<?php echo route('table.data', ['param' => $table, 'page' => $i, 'listtablecolumns' => 0, 'columnoperator' => 0, 'inputval' => 0, 'inputval2' => 0]); ?>">Page {{ $i }}</a></li>
                    @endfor
                </ul>
            </div>
            <div class="btn-group me-1">
                <button type="button" class="btn btn-sm btn-outline-secondary">Total: {{ $info['total_rows'] }}</button>
            </div>
        </div>
    </div>

    <style>
        .floatingTextarea {
            height: 10px;
            width: 233px;
        }
    </style>
    <script>
        jQuery(document).ready(function() {

            jQuery('#reset_button').click(function() {
                var search_url =
                    "{{ route('table.data', ['param' => $table_name, 'page' => 1, 'listtablecolumns' => 0]) }}";
                window.location.href = search_url;
            });

            var selectedValue =
                "{{ $listtablecolumns }}"; // assuming $selectedValue contains the value you want to select
            var tablecolumnattribute =
                "{{ $tablecolumnattribute }}"; // assuming $selectedValue contains the value you want to select
            if (selectedValue) {
                jQuery('#listtablecolumns option[value="select"]').prop('selected', false);
                jQuery('#listtablecolumns').val(selectedValue);
                jQuery('#tablecolumnattribute').val(tablecolumnattribute);
                jQuery('#listtablecolumns option[value="' + selectedValue + '"]').prop('selected', true);
            }

            var selectedValue =
                "{{ $ColumnOperator8 }}"; // assuming $selectedValue contains the value you want to select
            if (selectedValue) {
                jQuery('#ColumnOperator8 option[value="select"]').prop('selected', false);
                jQuery('#ColumnOperator8').val(selectedValue);
                jQuery('#ColumnOperator8 option[value="' + selectedValue + '"]').prop('selected', true);
            }
            jQuery('#listtablecolumns').on('change', function() {
                let selectedOption = $(this).find(':selected');
                let attributeValue = selectedOption.data('attribute');
                jQuery('#tablecolumnattribute').val(attributeValue);
            });



            var floatingTextarea2 = "{{ $floatingTextarea2 }}";

            if (floatingTextarea2) {
                jQuery("#textarea_2").show();
                jQuery('#floatingTextarea2').val(floatingTextarea2);
            }
            var floatingTextarea1 = "{{ $floatingTextarea1 }}";
            if (floatingTextarea1 == "") {
                jQuery("#textarea_1").hide();
                jQuery("#textarea_2").hide();
                jQuery('#floatingTextarea1').val('');
                jQuery('#floatingTextarea2').val('');
            } else {
                jQuery("#textarea_1").show();

            }
            jQuery('#ColumnOperator8').on('change', function() {
                var ColumnOperator8 = jQuery('#ColumnOperator8').val();
                if (ColumnOperator8 == 11 || ColumnOperator8 == 12) {
                    jQuery("#textarea_1").show();
                    jQuery("#textarea_2").show();
                } else if (ColumnOperator8 == 13 || ColumnOperator8 == 14) {
                    jQuery("#textarea_1").hide();
                    jQuery("#textarea_2").hide();
                    jQuery('#floatingTextarea1').val('');
                    jQuery('#floatingTextarea2').val('');
                } else {
                    jQuery("#textarea_1").show();
                    jQuery("#textarea_2").hide();
                    jQuery('#floatingTextarea1').val('');
                    jQuery('#floatingTextarea2').val('');
                }
            });
        });

        function checkEmpty() {
            var listtablecolumns = jQuery('#listtablecolumns').val();
            var ColumnOperator8 = jQuery('#ColumnOperator8').val();
            var floatingTextarea1 = jQuery('#floatingTextarea1').val();
            var floatingTextarea2 = jQuery('#floatingTextarea2').val();

            if (listtablecolumns == "select") {
                alert("Please Select Table Column Name");
                jQuery("#listtablecolumns").focus();
                return false;
            } else {
                if (ColumnOperator8 == 'select') {
                    alert("Please Select  Column Operator");
                    jQuery("#ColumnOperator8").focus();

                    return false;
                } else {

                    if (ColumnOperator8 == 11 || ColumnOperator8 == 12) {
                        if (floatingTextarea1 == '') {
                            alert("Please Type A between From Value");
                            jQuery("#floatingTextarea1").focus();
                            return false;
                        } else if (floatingTextarea2 == '') {
                            alert("Please Type A between To Value");
                            jQuery("#floatingTextarea2").focus();
                            return false;
                        }
                    } else if (ColumnOperator8 == 13 || ColumnOperator8 == 14) {
                        return true;
                    } else {
                        if (floatingTextarea1 == '') {
                            alert("Please Type Value");
                            jQuery("#floatingTextarea1").focus();
                            return false;
                        }
                    }
                }

            }
            return true;
        }
    </script>


    <div class="row">
        <div class="col-12">
            <form onsubmit="return checkEmpty();"
                action="{{ route('table.data', ['param' => $table_name, 'page' => 1, 'listtablecolumns' => 0]) }}"
                method="GET">
                <table>
                    <tr>
                        <td>Filter</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>
                            <select class="form-select" id="listtablecolumns" name="listtablecolumns">
                                <option selected value="select">Select</option>
                                @foreach ($table_columns as $item)
                                    <option value="{{ $item->column_name }}" data-attribute="{{ $item->data_type }}">
                                        {{ $item->column_name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="tablecolumnattribute" id="tablecolumnattribute" />
                        </td>
                        <td>
                            <select class="form-select" id="ColumnOperator8" name="ColumnOperator8">

                                <option selected value="select">Select</option>

                                @foreach ($operator as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td id="textarea_1">
                            <textarea class="form-control floatingTextarea" placeholder="" id="floatingTextarea1" name="floatingTextarea1">{{ $floatingTextarea1 }}</textarea>
                        </td>
                        <td id="textarea_2" style="display: none;">
                            <textarea class="form-control floatingTextarea" placeholder="" id="floatingTextarea2" name="floatingTextarea2"></textarea>
                        </td>
                        <td>

                            <button class="btn btn-info" type="submit"> <img
                                    src="{{ asset('public/assets/brand/search.svg') }}" alt="" srcset="">
                            </button>

                            <button class="btn btn-danger" id="reset_button" type="button">Reset</button>

                            {{-- id="search_button" --}}
                            {{-- <a class="btn btn-info" > </a> --}}
                        </td>
                    </tr>
                </table>
            </form>



        </div>
    </div>

    <hr>
    <div class="table-responsive small scrollable-table"
        style="width: 100%; height: 480px; overflow-x: auto; overflow-y: auto;">
        <table class="table table-striped table-hover table-sm table-responsive small">
            <thead class="sticky-header">
                <tr>
                    @foreach ($info['columns'] as $column)
                        <th scope="col">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>

                @php
                    $countdata = count($info['columns_lower']);
                @endphp
                
            @foreach ($info['rows'] as $row)
                <tr>
                    @foreach ($info['columns_lower'] as $col)
                        <td>{{ $row->{$col} ?? '-' }}</td>
                    @endforeach
                </tr>
            @endforeach
            
            @if (count($info['rows']) == 0)
                <tr>
                    <td colspan="{{ $countdata }}"> <span style="color:red;">No Data Found</span> </td>
                </tr>
            @endif
            
        </table>
    </div>
    <br>
    <br>
@endsection
