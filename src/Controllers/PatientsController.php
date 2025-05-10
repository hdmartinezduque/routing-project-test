<?php

namespace App\Controllers;

use App\utils\RespondHandle;

class PatientsController
{

    private $responder;

    public function __construct()
    {
        $this->responder = new RespondHandle();
    }

    public function index()
    {
        //patients = listpatients();
        $patients = [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Smith']
        ];
        RespondHandle::respond($patients);
    }

    public function get($patientId)
    {
        if (empty($patientId)) {
            RespondHandle::responseError('Patient ID is required', 400);
        }

        // $patient = getPatients();
        $patient = ['id' => $patientId, 'name' => "Patient $patientId"];
        RespondHandle::respond($patient);
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            RespondHandle::responseError('Invalid or missing JSON payload', 400);
        }

        if(!isset($data['name'], $data['age'])){
            RespondHandle::responseError('Missing required fiels', 400);
        }

        // addPatients();
        RespondHandle::respond([
            'message' => 'Patient created successfully',
            'patient' => $data
        ], 201);
    }


    public function update($patientId)
    {
        if (empty($patientId)) {
            RespondHandle::responseError('Patient ID is required', 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            RespondHandle::responseError('Invalid or missing JSON payload', 400);
        }

        if(!isset($data['name'], $data['age'])){
            RespondHandle::responseError('Missing required fiels', 400);
        }

        // updatePatients();
        RespondHandle::respond([
            'message' => "Patient with ID $patientId updated successfully",
            'updated_data' => $data
        ]);
    }

    public function delete($patientId)
    {
        if (empty($patientId)) {
            RespondHandle::responseError('Patient ID is required', 400);
        }

        // deletePatients();
        RespondHandle::respond([
            'message' => "Patient with ID $patientId deleted successfully"
        ]);
    }
}
