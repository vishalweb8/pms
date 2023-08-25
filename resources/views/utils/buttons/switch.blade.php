<div class="form-check form-switch form-switch-md ms-3 emp-table-switch" dir="ltr">
    @if($check == 1)
        <input class="form-check-input status-update" id="empswitch" type="checkbox" name="status" value="{{ $link ?? '#' }}" checked>
        <label class="form-check-label text-primary" for="empswitch">Active</label>
    @else
        <input class="form-check-input status-update" id="empswitch" type="checkbox" name="status" value="{{ $link ?? '#' }}">
        <label class="form-check-label text-danger" for="empswitch">Inactive</label>
    @endif
</div>
