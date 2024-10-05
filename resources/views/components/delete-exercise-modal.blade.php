<div class="modal fade" id="deleteModal{{ $exercise->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $exercise->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container d-flex pl-0">
                    <i class="fas fa-trash-alt text-danger fa-lg mt-2"></i>
                    <h5 class="modal-title ml-2" id="deleteModalLabel{{ $exercise->id }}">{{ $exercise->title }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted">{{ __("If you delete the exercise it will be gone from our website for not only you, but also for the people who submitted the exercise. Are you sure you want to proceed?") }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Cancel") }}</button>
                <form id="deleteForm{{ $exercise->id }}" class="deleteExerciseForm" method="POST" action="/exercises/{{ $exercise->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" data-loading-text="{{ __("Deleting...") }}" >{{ __("Delete") }}</button>
                </form>
            </div>
        </div>
    </div>
</div>