<form id="form" action="">
    {{csrf_field()}}
    <div class="form-group">
        <label>Role</label>
        <input type="text" name="first_name" class="form-control " value="{{$data->role}}" readonly>
    </div>
    <div class="form-group">
        <label>Status</label>
        <input type="text" name="first_name" class="form-control " value="{{$data->status}}" readonly>
    </div>
    <div class="form-group">
        <label>First name</label>
        <input type="text" name="first_name" class="form-control " value="{{$data->first_name}}" readonly>
    </div>
    <div class="form-group">
        <label>Last name</label>
        <input type="text" name="last_name" class="form-control" value="{{$data->last_name}}" readonly>
    </div>
    <div class="form-group">
        <label>Date of Birth</label>
        <input type="text" name="last_name" class="form-control" value="{{$data->date_of_birth}}" readonly>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{$data->email}}" readonly>
    </div>
    <div class="form-group">
        <label>Notes</label>
        <input type="text" name="notes" class="form-control" value="{{$data->notes}}" readonly>
    </div>
    <div class="form-group">
        <label>Api Token</label>
        <input type="text" name="notes" class="form-control" value="{{$data->api_token}}" readonly>
    </div>

</form>
