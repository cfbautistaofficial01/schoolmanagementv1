<?php namespace App\Controllers\API;
use App\Controllers\BaseController;
use App\Models\QualificationModel;
class QualificationController extends BaseController {
    
    // Get all non-archived qualifications
    public function index()
    {
        $model = new QualificationModel();

        // Get query parameters for pagination and filtering
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('perPage') ?? 10;
        $search = $this->request->getGet('search') ?? '';
        $status = $this->request->getGet('status') ?? '';

        // Start building the query
        $builder = $model;

        // Apply search filter
        if (!empty($search)) {
            $builder = $builder->like('qualification_name', $search)
                            ->orLike('level', $search)
                            ->orLike('category', $search);
        }

        // Apply status filter
        if ($status === 'active') {
            $builder = $builder->where('is_active', true);
        } elseif ($status === 'inactive') {
            $builder = $builder->where('is_active', false);
        }

        // Fetch paginated data
        $data = $builder->paginate($perPage, 'default', $page);

        // Prepare the response
        $response = [
            'data' => $data,
            'pagination' => [
                'total' => $model->pager->getTotal(),
                'perPage' => $model->pager->getPerPage(),
                'currentPage' => $model->pager->getCurrentPage(),
                'lastPage' => $model->pager->getLastPage(),
            ]
        ];

        return $this->response->setJSON($response);
    }

    // Get a single qualification by ID
    public function show($id = null) {
        $model = new QualificationModel();
        $data = $model->find($id);
        if ($data) {
            return $this->response->setJSON($data);
        }
        return $this->response->setStatusCode(404)->setJSON(['message' => 'Qualification not found']);
    }

    // Create a new qualification
    public function create() {
        $model = new QualificationModel();
        $data = $this->request->getJSON();
        if ($model->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Qualification added.']);
        }
        return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => $model->errors()]);
    }

    // Update an existing qualification
    public function update($id = null) {
        $model = new QualificationModel();
        $data = $this->request->getJSON();
        if ($model->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Qualification updated.']);
        }
        return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => $model->errors()]);
    }

    // Archive (soft delete) a qualification
    public function delete($id = null) {
        $model = new QualificationModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Qualification archived.']);
        }
        return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Qualification not found.']);
    }

    public function suggestCategory()
    {
        $qualificationName = $this->request->getGet('name');
        if (empty($qualificationName)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Qualification name is required.']);
        }

        $nameLower = strtolower($qualificationName);

        // Based on official TESDA sectors with common keywords
        $sectorMap = [
            'Information and Communication Technology (ICT)' => ['computer', 'web', 'programming', 'animation', 'contact center', 'telecom'],
            'Tourism' => ['housekeeping', 'baking', 'pastry', 'cookery', 'food', 'beverage', 'barista', 'bartending', 'tour guiding', 'events management'],
            'Metals and Engineering' => ['welding', 'machining', 'automotive', 'motorcycle', 'engine', 'mechanical'],
            'Construction' => ['carpentry', 'masonry', 'plumbing', 'electrical installation', 'scaffolding', 'heavy equipment'],
            'Agriculture, Forestry and Fishery' => ['agriculture', 'horticulture', 'animal', 'crop', 'organic', 'fish'],
            'Health, Social and Other Community Development Services' => ['caregiving', 'health care', 'hilot', 'wellness', 'massage'],
            'Processed Food and Beverages' => ['food processing', 'meat processing'],
            'TVET' => ['trainer methodology', 'assessor methodology'],
            'Maritime' => ['maritime', 'seafaring'],
        ];

        // Find the matching category
        foreach ($sectorMap as $sector => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($nameLower, $keyword)) {
                    // Found a match, return the sector
                    return $this->response->setJSON(['category' => $sector]);
                }
            }
        }

        // If no match is found, return a default
        return $this->response->setJSON(['category' => 'Miscellaneous']);
    }
}