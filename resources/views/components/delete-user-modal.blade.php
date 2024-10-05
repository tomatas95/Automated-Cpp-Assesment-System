<div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container d-flex pl-0">
                    <i class="fas fa-trash-alt text-danger fa-lg mt-3"></i>
                    <h5 class="modal-title ml-2" id="deleteModalLabel{{ $user->id }}">{{ $user->name }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted">{{ __("Are you sure you want to delete this user? All the submissions and the exercises the person has created will also be wiped off from the system. Do you wish to proceed?") }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Cancel") }}</button>
                <form id="deleteForm{{ $user->id }}" class="deleteUserForm" method="POST" action="/admin/users/{{ $user->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" data-loading-text={{ __("Deleting...") }}>{{ __("Delete") }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
