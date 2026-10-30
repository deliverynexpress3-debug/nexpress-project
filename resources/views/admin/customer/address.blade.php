@extends('admin.layout.page-app')
@section('page_title', __('label.address'))
@section('tab_title', __('label.address'))

@section('content')
	@include('admin.layout.sidebar')

	<div class="right-content">
		@include('admin.layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('label.address')}}</h1>

			<div class="border-bottom row mb-3">
				<div class="col-sm-10">
					<ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.customer.index') }}">{{__('label.users')}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{__('label.address')}}</li>
					</ol>
				</div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
  					<a href="{{ route('admin.customer.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.users')}}</a>
  				</div>
			</div>

			<!-- Add Address -->
			<div class="card custom-border-card mt-3">
                <h5 class="card-header">{{__('label.add_address')}}</h5>
                <div class="card-body">
                    <form id="address" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="customer_id" value="{{ $customer_id }}">
                        <div class="form-row">
                            <div class="col-md-2">
								<div class="form-group">
									<label>{{__('label.type')}}<span class="text-danger">*</span></label>
									<select class="form-control" name="type">
										<option value="1" selected>{{__('label.home')}}</option>
										<option value="2">{{__('label.work')}}</option>
										<option value="3">{{__('label.other')}}</option>
									</select>
								</div>
							</div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.address')}}<span class="text-danger">*</span></label>
                                    <textarea name="address" rows="1" class="form-control" placeholder="{{__('label.address_here')}}"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.area')}}<span class="text-danger">*</span></label>
                                    <textarea name="area" rows="1" class="form-control" placeholder="{{__('label.area_here')}}"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.city')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="city" class="form-control" placeholder="{{__('label.city_here')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.state')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="state" class="form-control" placeholder="{{__('label.state_here')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.country')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="country" class="form-control" placeholder="{{__('label.country_here')}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.pincode')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="pincode" class="form-control" placeholder="{{__('label.pincode_here')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.latitude')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="latitude" class="form-control" placeholder="{{__('label.latitude_here')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.longitude')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="longitude" class="form-control" placeholder="{{__('label.longitude_here')}}">
                                </div>
                            </div>
                        </div>
                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120" onclick="save_address()">{{__('label.save')}}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
            </div>

			<!-- Search && Table -->
            <div class="card custom-border-card mt-3">
                <div class="page-search mb-3">
                    <div class="input-group" title="Search">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                </div>

                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th>{{__('label.#')}}</th>
                                <th>{{__('label.type')}}</th>
                                <th>{{__('label.address')}}</th>
                                <th>{{__('label.area')}}</th>
                                <th>{{__('label.city')}}</th>
                                <th>{{__('label.state')}}</th>
                                <th>{{__('label.country')}}</th>
                                <th>{{__('label.pincode')}}</th>
                                <th>{{__('label.status')}}</th>
                                <th>{{__('label.action')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

			<!-- Edit Model -->
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="EditModelLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="EditModelLabel">{{__('label.edit_address')}}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_address">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.type')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="type" id="edit_type">
                                                <option value="1">{{__('label.home')}}</option>
                                                <option value="2">{{__('label.work')}}</option>
                                                <option value="3">{{__('label.other')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>{{__('label.area')}}<span class="text-danger">*</span></label>
                                            <textarea name="area" id="edit_area" rows="1" class="form-control" placeholder="{{__('label.area_here')}}"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.address')}}<span class="text-danger">*</span></label>
                                            <textarea name="address" id="edit_address" rows="2" class="form-control" placeholder="{{__('label.address_here')}}"></textarea>
                                        </div>
                                    </div>
                                  
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.city')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="city" id="edit_city" class="form-control" placeholder="{{__('label.city_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.state')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="state" id="edit_state" class="form-control" placeholder="{{__('label.state_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.country')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="country" id="edit_country" class="form-control" placeholder="{{__('label.country_here')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.pincode')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="pincode" id="edit_pincode" class="form-control" placeholder="{{__('label.pincode_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.latitude')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="latitude" id="edit_latitude" class="form-control" placeholder="{{__('label.latitude_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.longitude')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="longitude" id="edit_longitude" class="form-control" placeholder="{{__('label.longitude_here')}}">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="edit_id">
                                <input type="hidden" name="customer_id" id="edit_user_id">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="update_address()">{{__('label.update')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		</div>
	</div>
@endsection

@section('pagescript')
	<script>
		$(document).ready(function() {
			var table = $('#datatable').DataTable({
                dom: "<'top'f>rt<'row'<'col-2'i><'col-1'l><'col-9'p>>",
                searching: false,
                autoWidth: false,
                responsive: true,
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [10, 100, 500, -1],
                    [10, 100, 500, "All"]
                ],
                language: {
                    paginate: {
                        previous: "<i class='fa-solid fa-chevron-left'></i>",
                        next: "<i class='fa-solid fa-chevron-right'></i>"
                    }
                },
                ajax: {
                    url: "{{ route('admin.customer.address.index', [$customer_id]) }}",

                    data: function(d) {
                        d.input_search = $('#input_search').val();
                    },
                },

             
				columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
					{
						data: 'type',
						name: 'type',
						render: function(data) {
                            if (data == 1) {
                                return "Home";
                            } else if (data == 2) {
                                return "Work";
                            } else if (data == 3) {
                                return "Other";
                            } else {
                                return "-";
                            }
                        }
					},
                    {
						data: 'address',
						name: 'address',
						render: function(data) {
                            return data ? data : "-";
                        }
					},
                    {
						data: 'area',
						name: 'area',
						render: function(data) {
                            return data ? data : "-";
                        }
					},
                    {
						data: 'city',
						name: 'city',
						render: function(data) {
                            return data ? data : "-";
                        }
					},
                    {
						data: 'state',
						name: 'state',
						render: function(data) {
                            return data ? data : "-";
                        }
					},
                    {
						data: 'country',
						name: 'country',
						render: function(data) {
                            return data ? data : "-";
                        }
					},
                    {
						data: 'pincode',
						name: 'pincode',
						render: function(data) {
                            return data ? data : "-";
                        }
					},
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
					{ data: 'action', name: 'action', orderable: false, searchable: false }
				],
			});
			$('#input_search').keyup(function() {
				table.draw();
			});
        });

		function save_address(){

			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var formData = new FormData($("#address")[0]);
                $.ajax({
                    type:'POST',
                    url:'{{ route("admin.customer.address.store") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'address', '{{ route("admin.customer.address.index", [$customer_id]) }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
		}

		$(document).on("click", ".edit_address", function() {
            var id = $(this).data('id');
            var customer_id = $(this).data('user_id');
            var type = $(this).data('type');
            var address = $(this).data('address');
            var area = $(this).data('area');
            var city = $(this).data('city');
            var state = $(this).data('state');
            var country = $(this).data('country');
            var pincode = $(this).data('pincode');
            var latitude = $(this).data('latitude');
            var longitude = $(this).data('longitude');

            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_user_id").val(customer_id);
            $(".modal-body #edit_type").val(type).attr("selected","selected");
            $(".modal-body #edit_address").val(address);
            $(".modal-body #edit_area").val(area);
            $(".modal-body #edit_city").val(city);
            $(".modal-body #edit_state").val(state);
            $(".modal-body #edit_country").val(country);
            $(".modal-body #edit_pincode").val(pincode);
            $(".modal-body #edit_latitude").val(latitude);
            $(".modal-body #edit_longitude").val(longitude);
        });

        function update_address() {

			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var formData = new FormData($("#edit_address")[0]);
                
                var Edit_Id = $("#edit_id").val();
                var url = '{{ route("admin.customer.address.update", ":id") }}';
                    url = url.replace(':id', Edit_Id);

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        $('#EditModel').modal('toggle');
                        get_responce_message(resp, 'edit_address', '{{ route("admin.customer.address.index", [$customer_id]) }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
        }

        
        function change_status(id, status) {

			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var url = `{{ route('admin.customer.address.status', '') }}/${id}`;

                $.ajax({
                    type: "Post",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(resp) {
                        $("#dvloader").hide();

                        if (resp.status == 200) {
                            if (resp.status_code == 1) {
                                $('#' + id).text('{{__("label.show")}}').removeClass('hide-btn').addClass('show-btn');
                            } else {
                                $('#' + id).text('{{__("label.hide")}}').removeClass('show-btn').addClass('hide-btn');
                            }
                            toastr.success(resp.success);
                        } else {
                            toastr.error(resp.errors);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
        };
	</script>
@endsection