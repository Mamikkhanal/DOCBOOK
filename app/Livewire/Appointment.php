<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Appointment extends Component
{
    public $problem = '';
    public $specializationResult = '';

    protected $listeners = ['specializationSuggested'];

    public function suggestSpecialization(): void
    {
        try {
            // Make a POST request to the specialization suggestion route
            $response = Http::post(route('specialization-suggestion'), [
                'problem' => $this->problem,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['specialization'])) {
                $this->emit('specializationSuggested', $result['specialization']);
            } else {
                $this->emit('specializationSuggested', 'Unable to fetch specialization suggestion.');
            }
        } catch (\Exception $e) {
            $this->emit('specializationSuggested', 'Error occurred: ' . $e->getMessage());
        }
    }

    public function specializationSuggested(string $specialization): void
    {
        $this->specializationResult = $specialization;
    }

    public function render()
    {
        return view('livewire.appointment');
    }
}
