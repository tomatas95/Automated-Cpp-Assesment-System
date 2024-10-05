<div class="modal fade" id="privateModal{{ $exercise->id }}" tabindex="-1" role="dialog" aria-labelledby="privateModal{{ $exercise->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container d-flex pl-0">
                    <i class="fas fa-lock text-warning fa-lg mt-2"></i>
                    <h5 class="modal-title ml-2" id="privateModal{{ $exercise->id }}">{{ __("Enter Password for") }} {{ $exercise->title }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted">{{ __("This exercise is private and requires a password to access. Please enter the password below:") }}</p>
                <form id="passwordForm passwordForm{{ $exercise->id }}" class="privateForm" method="POST" action="/exercises/access/{{ $exercise->id }}">
                    @csrf
                    <div class="form-group">
                        <label for="exercise_password{{ $exercise->id }}" class="text-muted">{{ __("Exercise Password") }}</label>
                        <div class="input-group">
                            <input type="password" class="form-control hint-input" name="exercise_password_{{ $exercise->id }}" id="exercise_password{{ $exercise->id }}" placeholder="{{ __("Enter Exercise Password...") }}">
                            <span id="toggleExercisePassword{{ $exercise->id }}" class="togglePassword">
                                <i class="fas fa-eye fa-lg toggleIcon" id="toggleExercisePasswordIcon{{ $exercise->id }}"></i>
                            </span>
                        </div>
                        @error('exercise_password_' . $exercise->id)
                        <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __("Proceed") }}</button>
            </form>
            </div>
        </div>
    </div>
</div>
