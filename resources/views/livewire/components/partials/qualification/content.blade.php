@if($editMode)
    <form wire:submit.prevent="update">
        @include('livewire.components.partials.qualification.form-fields')
        <div class="form-group">
            <button type="button" class="btn btn-outline-secondary" wire:click="toggleEditMode()">{{ ucfirst(__('laravel-crm::lang.cancel')) }}</button>
            <button type="submit" class="btn btn-primary">{{ ucfirst(__('laravel-crm::lang.save')) }}</button>
        </div>
    </form>
@else
<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $qualification->name }} @include('livewire.components.partials.qualification.actions', ['qualification' => $qualification])</h5> 
        <p class="card-text">
            <span class="badge badge-secondary">{{ $qualification->start_at->format('h:i A') }} on {{ $qualification->start_at->toFormattedDateString() }}</span>
            to
            <span class="badge badge-secondary">{{ $qualification->finish_at->format('h:i A') }} on {{ $qualification->finish_at->toFormattedDateString() }}</span>
        </p>
        @if($qualification->univercity)
            <hr>
            <h6><strong>University</strong></h6>
            <p>{{ $qualification->univercity }}</p>
        @endif
        @if($qualification->institute)
            <hr>
            <h6><strong>Institute</strong></h6>
            <p>{{ $qualification->institute }}</p>
        @endif
        @if($qualification->location)
            <hr>
            <h6><strong>Location</strong></h6>
            <p>{{ $qualification->location }}</p>
        @endif
        @if($qualification->percentage)
            <hr>
            <h6><strong>Percentage</strong></h6>
            <p>{{ $qualification->percentage }}</p>
        @endif
        @if($qualification->description)
            <hr>
            <h6><strong>Description</strong></h6>
            <p>{{ $qualification->description }}</p>
        @endif
    </div>
</div>
<br>
@endif

@push('livewire-js')
        <script>
            $(document).ready(function () {
                $(document).on("change", ".qualifications #inputCreateForm input[name='start_at']", function () {
                    @this.set('start_at', $(this).val());
                });

                $(document).on("change", ".qualifications #inputCreateForm input[name='finish_at']", function () {
                    @this.set('finish_at', $(this).val());
                });

                $(document).on("change", '.qualifications select[name="guests[]"]', function (e) {
                    var data = $('select[name="guests[]"]').select2("val");
                    @this.set('guests', data);
                });

                window.addEventListener('qualifyEditModeToggled', event => {
                    bindDateTimePicker_Qualification();
                    bindSelect2_Qualification();
                });

                window.addEventListener('qualificationAddOn', event => {
                    $('.nav-activities li a#tab-qualifications').tab('show')
                    bindDateTimePicker_Qualification()
                    bindSelect2_Qualification();
                });

                $('.nav-tabs a#tab-qualifications').on('shown.bs.tab', function(event){
                    bindDateTimePicker_Qualification()
                    bindSelect2_Qualification();
                });

                window.addEventListener('qualificationFieldsReset', event => {
                    bindDateTimePicker_Qualification();
                    bindSelect2_Qualification();
                });
            });

            function bindDateTimePicker_Qualification(){
                $('.qualifications input[name="start_at"]').datetimepicker({
                    timepicker:true,
                    format: '{{ $dateFormat }} H:i',
                });
                $('.qualifications input[name="finish_at"]').datetimepicker({
                    timepicker:true,
                    format: '{{ $dateFormat }} H:i',
                });
            }

            function bindSelect2_Qualification(){
                console.log('hit');
            }
        </script>
    @endpush