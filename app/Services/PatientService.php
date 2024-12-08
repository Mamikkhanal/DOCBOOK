<?php

namespace App\Services;

use Auth;
use App\Models\Patient;
use App\Repositories\PatientRepository;

class PatientService
{
    protected $patientRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function getAllPatients()
    {
        return $this->patientRepository->all();
    }

    public function getPatient($id)
    {
        $patient = Patient::where('user_id', Auth::user()->id)->first();

        if ($patient->id == $id) {
            return $this->patientRepository->find($id);
        } else
            return response()->json(
                [
                    'status' => false,
                    'message' => 'You are not authorized to view this patient.'
                ],
                403
            );
    }

    public function createPatient(array $data)
    {
        return $this->patientRepository->create($data);
    }

    public function updatePatient($id, array $data)
    {
        $patient = Patient::where('user_id', Auth::user()->id)->first();

        if ($patient->id == $id) {
            return $this->patientRepository->update($id, $data);
        } else
            return response()->json(
                [
                    'status' => false,
                    'message' => 'You are not authorized to update this patient.'
                ],
                403
            );
    }

    public function deletePatient($id)
    {
        $patient = Patient::where('user_id', Auth::user()->id)->first();

        if ($patient->id == $id) {
            return $this->patientRepository->delete($id);
        } else
            return response()->json(
                [
                    'status' => false,
                    'message' => 'You are not authorized to delete this patient.'
                ],
                403
            );
    }
}
