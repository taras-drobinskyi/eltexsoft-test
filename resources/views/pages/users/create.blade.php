<form id="form" action="{{ url('/users/') }}">
    {{csrf_field()}}
    {{method_field("POST")}}
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
        <div class="form-check">
            <label>Status </label>
            <label class="form-check-label">
                <input id="checkboxActive" class="form-check-input checkbox" name="status" type="checkbox" value="active">
                <input id="checkboxHidden" class="form-check-input" name="status" type="hidden" value="inactive">
                Active
                <span class="form-check-sign">
              <span class="check"></span>
          </span>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label>First name</label>
        <input type="text" name="first_name" class="form-control" required>
        <div id="first_name-error" class="error text-danger pl-3" for="name" style="display: none;">
            <strong>{{ $errors->first('first_name') }}</strong>
        </div>
    </div>
    <div class="form-group">
        <label>Last name</label>
        <input type="text" name="last_name" class="form-control" required>
        <div id="last_name-error" class="error text-danger pl-3" for="name" style="display: none;">
        </div>
    </div>
    <div class="form-group">
        <label>Date of birth</label>
        <input type="date" name="date_of_birth" class="form-control" required>
        <div id="date_of_birth-error" class="error text-danger pl-3" for="name" style="display: none;">
        </div>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
        <div id="email-error" class="error text-danger pl-3" for="name" style="display: none;">
        </div>
    </div>
    <div class="form-group">
        <label>Notes</label>
        <input type="text" name="notes" class="form-control">
        <div id="notes-error" class="error text-danger pl-3" for="name" style="display: none;">
        </div>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
        <div id="password-error" class="error text-danger pl-3" for="name" style="display: none;">
        </div>
    </div>
    <div class="form-group">
        <label>Confirm password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
        <div id="password_confirmation-error" class="error text-danger pl-3" for="name" style="display: none;">
        </div>
    </div>

</form>

