<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Qualification as QualificationModel;
use VentureDrake\LaravelCrm\Services\SettingService;
use VentureDrake\LaravelCrm\Traits\HasGlobalSettings;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class Qualify extends Component
{
    use NotifyToast;

    private $settingService;
    public $qualification;
    public $model;
    public $name;
    public $institute;
    public $univercity;
    public $description;
    public $start_at;
    public $finish_at;
    public $percentage;
    public $location;
    public $editMode = false;
    public $showRelated = false;

    protected $listeners = [
        'refreshComponent' => '$refresh',
     ];

    public function boot(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function mount(QualificationModel $qualification, $view = 'qualify')
    {
        $this->qualification = $qualification;
        $this->name = $qualification->name;
        $this->institute = $qualification->institute;
        $this->univercity = $qualification->univercity;
        $this->description = $qualification->description;
        $this->start_at = $qualification->start_at;
        $this->finish_at = $qualification->finish_at;
        $this->percentage = $qualification->percentage;
        $this->location = $qualification->location;

        if($this->settingService->get('show_related_activity')->value == 1) {
            $this->showRelated = true;
        }

        $this->view = $view;
    }

    /**
     * Returns validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => "required",
            'description' => "nullable",
            'start_at' => 'required',
            'finish_at' => 'required',
            'institute' => 'required',
            'univercity' => 'required',
            'percentage' => 'required',
            'location' => "required",
        ];
    }

    public function update()
    {
        die('update');
        $this->validate();

        $this->qualification->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'start_at' => $data['start_at'],
            'finish_at' => $data['finish_at'],
            'location' => $this->location,
            'percentage' => $this->percentage,
            'univercity' => $this->univercity,
            'institute' => $this->institute,
        ]);

        $this->emit('qualificationCompleted');
        $this->notify(
            ucfirst(trans('laravel-crm::lang.qualification_completed'))
        );
    }

    public function delete()
    {
        $this->qualification->delete();

        $this->emit('qualificationDeleted');
        $this->notify(
            ucfirst(trans('laravel-crm::lang.qualification_deleted'))
        );
    }

    public function toggleEditMode()
    {
        $this->editMode = ! $this->editMode;
        $this->dispatchBrowserEvent('qualifyEditModeToggled');
    }

    public function render()
    {
        return view('livewire.components.'.$this->view);
    }
}
