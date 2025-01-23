<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\DiagnosisRequest;
use App\Models\Diagnosis;
use App\Services\ChatGPTService;
use App\Traits\SendsApiResponse;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    use SendsApiResponse;
    public function history(BaseRequest $request)
    {
        $user = $request->user();

        $diagnoses = $user->diagnoses;

        return $this->with($diagnoses)->success();
    }

    public function getDiagnosis(DiagnosisRequest $request, ChatGPTService $chatGPTService)
    {
        $description = $request->skin_issue_description;

        $chatgptResponse = $chatGPTService->getDiagnosis($description);

        $val = new Diagnosis;

        $val->skin_issue_description = $description;
        $val->chatgpt_response = $chatgptResponse;
        $val->user_id = $request->user()->id;

        $val->save();

        return $this->with($chatgptResponse)->success();
    }
}
