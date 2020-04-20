<form id="form" action="{{ url('/users/'.$data->id) }}">
    {{csrf_field()}}
    {{method_field("PUT")}}

    @can('changeStatus')
        <div class="form-group">
            <div class="form-check">
                <label>Status </label>
                <label class="form-check-label">
                    <input id="checkboxActive" class="form-check-input checkbox" name="status" type="checkbox"
                           value="active" {{$data->status == 'active' ? 'checked': ''}}>
                    <input id="checkboxHidden" class="form-check-input" name="status" type="hidden" value="inactive" {{$data->status == 'inactive' ? 'checked': ''}}>
                    Active
                    <span class="form-check-sign">
              <span class="check"></span>
          </span>
                </label>
            </div>
        </div>
    @endcan
    @can('edit')
        <div class="form-group">
            {{--        <label >Role</label>--}}
            <select class="form-control" name="role" data-style="btn btn-link"
                    id="exampleFormControlSelect1">
                <option value="admin" selected>Admin</option>
                <option value="moderator">Moderator</option>
                <option value="creator">Creator</option>
            </select>
            <div id="status-error" class="error text-danger pl-3" for="role" style="display: none;">
            </div>
        </div>
        <br>
        <div class="form-group">
            <label>First name</label>
            <input type="text" name="first_name" class="form-control" value="{{$data->first_name}}" required>
            <div id="first_name-error" class="error text-danger pl-3" for="name" style="display: none;">
                <strong>{{ $errors->first('first_name') }}</strong>
            </div>
        </div>
        <div class="form-group">
            <label>Last name</label>
            <input type="text" name="last_name" class="form-control" value="{{$data->last_name}}" required>
            <div id="last_name-error" class="error text-danger pl-3" for="name" style="display: none;">
            </div>
        </div>
        <div class="form-group">
            <label>Date of Birth</label>
            <input type="text" name="date_of_birth" class="form-control" value="{{ \Carbon\Carbon::parse($data->date_of_birth)->format('Y-m-d')}}" required>
            <div id="date_of_birth-error" class="error text-danger pl-3" for="name" style="display: none;">
            </div>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{$data->email}}" required>
            <div id="email-error" class="error text-danger pl-3" for="name" style="display: none;">
            </div>
        </div>
        <div class="form-group">
            <label>Notes</label>
            <input type="text" name="notes" class="form-control" value="{{$data->notes}}">
            <div id="notes-error" class="error text-danger pl-3" for="name" style="display: none;">
            </div>
        </div>
    @endcan

</form>
