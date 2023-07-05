<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignRequest;
use App\Repository\Interfaces\DocRepositoryInterface;

class DocController extends Controller
{
    private $docRepository;

    public function __construct(DocRepositoryInterface $docRepository)
    {
        $this->docRepository = $docRepository;
    }

    public function assignUserToDocument(AssignRequest $request)
    {
        return $this->docRepository->assignDocToUser($request->input('user_id'), $request->input('user_role'));
    }

    public function getAssignedDocs()
    {
        $assignedDocs = $this->docRepository->getAssignedDocs()->get()->all();

        if (empty($assignedDocs)) {
            $this->docRepository->jsonErrorResponse('No assigned documents available');
        }

        return $assignedDocs;
    }
}
