<div>
    <form wire:submit.prevent="suggestSpecialization">
        <!-- Problem Description Input -->
        <div>
            <label for="problem">Describe Your Problem</label>
            <input type="text" id="problem" wire:model="problem" placeholder="Describe your health problem" />
            <small>Describe your health problem in detail to get a specialization suggestion.</small>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Suggest</button>
    </form>

    <!-- Specialization Result -->
    @if ($specializationResult)
        <div class="mt-3">
            <h5>Specialization Suggestion:</h5>
            <p>{{ $specializationResult }}</p>
        </div>
    @endif
</div>
