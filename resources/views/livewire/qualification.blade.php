<div class="qualifications">
    @if($showForm)
        <form wire:submit.prevent="create" id="inputCreateForm">
            @include('livewire.components.partials.qualification.form-fields')
            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ ucfirst(__('laravel-crm::lang.save')) }}</button>
            </div>
        </form>
        <hr/>
    @endif
    @if($qualifications && $qualifications->count() > 0)
    <ul class="list-unstyled">
        @foreach($qualifications as $qualification)
            @livewire('qualify',[
                'qualification' => $qualification
            ], key($qualification->id))
            <!-- @include('livewire.components.partials.qualification.content', ['qualification' => $qualification]) -->
        @endforeach
    </ul>
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
                    bsCustomFileInput.init()
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

            function bindDateTimePicker_Qualifications(){
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
</div>


