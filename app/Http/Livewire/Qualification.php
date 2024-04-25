<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Qualification as QualificationModel;
use VentureDrake\LaravelCrm\Services\SettingService;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class Qualification extends Component
{
    use NotifyToast;

    private $settingService;
    public $model;
    public $qualifications;
    public $name;
    public $institute;
    public $univercity;
    public $description;
    public $start_at;
    public $finish_at;
    public $percentage;
    public $location;
    public $showForm = true;
    public $editMode = false;

    protected $listeners = [
        'addMeetingActivity' => 'addMeetingOn',
        'qualificationCompleted' => 'getQualifications',
        'qualificationDeleted' => 'getQualifications',
     ];

    public function boot(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function mount($model)
    {
        $this->model = $model;
        $this->getQualifications();

        if (! $this->qualifications || ($this->qualifications && $this->qualifications->count() < 1)) {
            $this->showForm = true;
        }
    }

    public function create()
    {
        $data = $this->validate([
            'name' => "required",
            'description' => "nullable",
            'start_at' => 'required',
            'finish_at' => 'required',
            'institute' => 'required',
            'univercity' => 'required',
            'percentage' => 'required',
            'location' => "required",
        ]);
        $insert = [
            'name' => $data['name'],
            'description' => $data['description'],
            'start_at' => $data['start_at'],
            'finish_at' => $data['finish_at'],
            'location' => $this->location,
            'percentage' => $this->percentage,
            'univercity' => $this->univercity,
            'institute' => $this->institute,
            'user_owner_id' => auth()->user()->id,
            'user_assigned_id' => auth()->user()->id,
        ];
        if (get_class($this->model)  === "VentureDrake\LaravelCrm\Models\Client") {
            $qualification = $this->model->qualification()->create($insert);
        } else {
            $lead = \VentureDrake\LaravelCrm\Models\Lead::find($this->model->id);
            $qualification = $lead->client->qualification()->create($insert);
        }
        $this->model->activities()->create([
            'causable_type' => auth()->user()->getMorphClass(),
            'causable_id' => auth()->user()->id,
            'timelineable_type' => $this->model->getMorphClass(),
            'timelineable_id' => $this->model->id,
            'recordable_type' => $qualification->getMorphClass(),
            'recordable_id' => $qualification->id,
        ]);

        $this->notify(
            ucfirst(trans('laravel-crm::lang.qualification_created'))
        );

        $this->resetFields();
    }

    public function getQualifications()
    {
        $qualificationIds = [];
        if (get_class($this->model)  === "VentureDrake\LaravelCrm\Models\Client") {
            $query = $this->model->qualification()->where('user_assigned_id', auth()->user()->id);
            $results = $query->get();
        } else {
            $lead = \VentureDrake\LaravelCrm\Models\Lead::find($this->model->id);
            $query = $lead->client->qualification()->where('user_assigned_id', auth()->user()->id);
            $results = $query->get();
        }

        foreach($results as $qualification) {
            $qualificationIds[] = $qualification->id;
        }
        
        // if($this->settingService->get('show_related_activity')->value == 1 && method_exists($this->model, 'contacts')) {
        //     foreach($this->model->contacts as $contact) {
        //         foreach ($contact->entityable->meetings()->where('user_assigned_id', auth()->user()->id)->latest()->get() as $meeting) {
        //             $meetingIds[] = $meeting->id;
        //         }
        //     }
        // }

        if(count($qualificationIds) > 0) {
            $this->qualifications = QualificationModel::whereIn('id', $qualificationIds)->latest()->get();
        }

        $this->emit('refreshActivities');
    }

    private function resetFields()
    {
        $this->reset('name', 'description', 'start_at', 'finish_at', 'institute', 'univercity', 'percentage', 'location');

        $this->dispatchBrowserEvent('qualificationFieldsReset');

        $this->addQualificationToggle();

        $this->getQualifications();
    }

    public function addQualificationToggle()
    {
        $this->dispatchBrowserEvent('taskEditModeToggled');
    }

    function update() {
        die('hit');
    }
    public function render()
    {
        return view('livewire.qualification');
    }
}
