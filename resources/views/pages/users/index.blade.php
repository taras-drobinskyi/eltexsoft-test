@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('User Management')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @can('create')
                    <button type="button" class="btn btn-primary btn-modal" data-source="{{ url('new/user') }}"
                            data-toggle="modal" data-target="#modal" data-title="Create Data" data-button="Save">
                        Create Data
                    </button>
                    @endcan

                    <div class="modal fade " id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="btn-save">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">USERS</h4>
                            {{--                   <p class="card-category"> Here is a subtitle for this table</p> --}}
                        </div>
                        <div class="card-body">
                            {{--                   <div class="table-responsive"> --}}
                            <table class="table table-striped" id="table">
                                <thead class=" text-primary">
                                <th>#</th>
                                <th>Status</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Last Name</th>
                                <th>Date of Birth</th>
                                <th>Action</th>
                                </thead>
                            </table>
                            {{--                   </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script type="text/javascript">
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $(function () {

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('users') !!}',

                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'status', name: 'status'},
                    {data: 'email', name: 'email'},
                    {data: 'first_name', name: 'first name'},
                    {data: 'last_name', name: 'last name'},
                    {data: 'date_of_birth', name: 'last name'},
                    {data: 'action',},
                ]
            });
        });

    </script>

    <script type="text/javascript">


        $(document).on('click', '.btn-modal', function () {
            var title = $(this).attr('data-title');
            var datasource = $(this).attr('data-source');
            var button = $(this).attr('data-button');

            $(".modal-title").text(title);
            $("#btn-save").text(button);
            $.get(datasource, function (data) {
                $(".modal-body").html(data);
            });
        })


        $(document).on('click', '.btn-delete', function () {
            var url = $(this).attr('data-url');

            $.ajax({
                url: url,
                method: "DELETE",
                dataType: "JSON",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (d) {
                    // md.showNotification('top','left');
                    var title = d.title;
                    var message = d.message;
                    var type = d.type;
                    var state = d.state;
                    if (d.type == 'danger') {
                        $("#modal").modal("show");

                    } else {

                        $("#modal").modal("hide");
                        $('#table').DataTable().ajax.reload();
                    }

                    $.notify({
                        title: title,
                        message: message,

                    }, {
                        z_index: 10000,
                        type: type
                    });
                },

            })
        });
        $(document).on('click', '#btn-save', function () {
            var form = $("#form");

            // console.log(form.serialize());

            if ($('#checkboxActive').prop('checked') === true){
                $('#checkboxHidden').attr("disabled", true);
            }

            $.ajax({
                url: form.attr('action'),
                method: "POST",
                data: form.serialize(),
                beforeSend: function(){
                  $('.has-danger').removeClass('has-danger')
                  $('.error').hide()
                },
                success: function (d) {
                    // md.showNotification('top','left');
                    var title = d.title;
                    var message = d.message;
                    var type = d.type;
                    var state = d.state;
                    if (d.type == 'danger') {
                        $("#modal").modal("show");

                    } else {

                        $("#modal").modal("hide");
                        $('#table').DataTable().ajax.reload();
                    }

                    $.notify({
                        title: title,
                        message: message,

                    }, {
                        z_index: 10000,
                        type: type
                    });
                },
                error: function (data) {
                    console.log(data.responseJSON)
                    jQuery.each(data.responseJSON.errors, function (key, value) {
                        $('#' + key + '-error').show()
                        $('#' + key + '-error').parent().addClass('has-danger')
                        $('#' + key + '-error').html('<strong>' + value + '</strong>')
                    });
                }
            })
        });
    </script>
    <style type="text/css">
        .dataTables_filter {
            float: right;
        }

        .dataTables_paginate {
            float: right;
        }
    </style>
@endsection
