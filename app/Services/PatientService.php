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
        if(Auth::user()->role == 'admin'){
            $data = $this->patientRepository->all();
            return response()->json(['status' => true, 'data' => $data], 200);
        }
        else{
            return response()->json(
                [
                    'status' => false,
                    'message' => 'You are not authorized to view this data.'
                ],
                403
            );
        }   
    }

    public function getPatient($id)
    {
        $user= Auth::user();
        if ($user->role == 'patient' && $user->patient->id == $id) {
            $data =$this->patientRepository->find($id);

            return response()->json(['status' => true, 'data' => $data], 200);
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
        $user= Auth::user();
        if ($user->role == 'patient' && $user->patient->id == $id) {
            $this->patientRepository->update($id, $data);

            return response()->json(['status' => true, 'message' => 'Patient updated successfully'], 200);
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
        $user= Auth::user();
        if ($user->role == 'patient' && $user->patient->id == $id) {
            $this->patientRepository->delete($id);
            return response()->json(['status' => true, 'message' => 'Patient deleted successfully'], 200);
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
