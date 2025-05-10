<?php

namespace App\Controllers;

use App\utils\RespondHandle;

class MetricsController
{

    private $responder;

    public function __construct()
    {
        $this->responder = new RespondHandle();
    }


    public function index($patientId)
    {
        if (empty($patientId)) {
            RespondHandle::respond('Patient ID is required', 400);
        }

        // //patientsMetrics = listpatientsMetrics();
        $metrics = [
            ['id' => 1, 'type' => 'Blood Pressure', 'value' => '120/80'],
            ['id' => 2, 'type' => 'Heart Rate', 'value' => '72 bpm']
        ];
        RespondHandle::respond(['patientId' => $patientId,'metrics' => $metrics]);

    }

    public function get($patientId, $metricId)
    {
        if (empty($patientId) || empty($metricId)) {
            RespondHandle::responseError('Patient ID and Metric ID are required', 400);

        }
        $metric = [
            'id' => $metricId,
            'patientId' => $patientId,
            'type' => 'Blood Pressure',
            'value' => '120/80'
        ];
        RespondHandle::respond($metric);
    }

    public function create($patientId)
    {
        if (empty($patientId)) {
            RespondHandle::responseError('Patient ID is required', 400);
        }   

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            RespondHandle::responseError('Invalid or missing JSON payload', 400);
        }

        if(!isset($data['type'], $data['value'])){
            RespondHandle::responseError('Missing required fiels', 400);
        }

        // addPatientsMetrics();
        RespondHandle::respond([
            'message' => "Metric created successfully for patient ID $patientId",
            'metric' => $data
           ], 201);
    }

    public function update($patientId, $metricId)
    {
        if (empty($patientId) || empty($metricId)) {
            RespondHandle::responseError('Patient ID and Metric ID are required', 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            RespondHandle::responseError('Invalid or missing JSON payload', 400);
        }

        if(!isset($data['value'])){
            RespondHandle::responseError('Missing required fiels', 400);
        }

        // // updatePatientsMetric();
        RespondHandle::respond([
            'message' => "Metric ID $metricId updated for patient ID $patientId",
            'updated_data' => $data
        ]);
    }

    public function delete($patientId, $metricId)
    {
        if (empty($patientId) || empty($metricId)) {
            RespondHandle::responseError('Patient ID and Metric ID are required', 400);
        }

        // // deletePatientsMetric();
        RespondHandle::respond([
            'message' => "Metric ID $metricId deleted for patient ID $patientId"
        ]);
    }

}
