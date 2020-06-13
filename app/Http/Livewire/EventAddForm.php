<?php

namespace App\Http\Livewire;

use App\Models\Event;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class EventAddForm extends Component
{
    use WithFileUploads;

    public $eventName;
    public $contactName;
    public $contactEmail;
    public $allowedParticipants;
    public $banner;
    public $event;

    public function mount($event)
    {
        $this->event = null;

        if ($event) {
            $this->event = $event;

            $this->eventName = $this->event->event_name;
            $this->contactName = $this->event->contact_person;
            $this->contactEmail = $this->event->contact_email;
            $this->allowedParticipants = $this->event->allowed_participant;
        }
    }

    public function submit()
    {
        $this->validate([
            'eventName' => ['required', 'min:3'],
            'contactName' => ['required', 'min:3'],
            'contactEmail' => ['required', 'email'],
            'allowedParticipants' => ['required', 'numeric'],
            'banner' => ['required', 'file'],
        ]);

        $filePath = $this->banner->store('public/banners');

        $event = [
            'event_name' => $this->eventName,
            'contact_person' => $this->contactName,
            'contact_email' => $this->contactEmail,
            'allowed_participant' => $this->allowedParticipants,
            'banner' => $filePath,
        ];

        if ($this->event) {
            Event::find($this->event->id)
                ->update($event);
        } else {
            $event['identifier'] = Str::random(10);
            Event::create($event);
        }

        return $this->redirectRoute('home');
    }

    public function render()
    {
        return view('livewire.event-add-form');
    }
}
