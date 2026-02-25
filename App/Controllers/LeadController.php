<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\LeadRepository;
use App\Services\PolicyService;

class LeadController {
    private LeadRepository $repository;

    public function __construct() {
        $this->repository = new LeadRepository();
    }

    public function index(Request $req, Response $res) {
        $user = $req->user;

        $page = (int) ($req->query['page'] ?? 1);
        $limit = (int) ($req->query['limit'] ?? 20);
        $offset = ($page - 1) * $limit;

        $leads = [];
        $total = 0;

        if (!PolicyService::canViewAny($req->user)) {
            $leads = $this->repository->findAll($limit, $offset);
            $total = $this->repository->countAll();
        }
        else {
            $leads = $this->repository->findByUser($user['sub'], $limit, $offset);
            $total = $this->repository->countByUser($user['sub']);
        }

        $res->status(200);
        $res->json([
            "data" => $leads,
            "meta" => [
                "page" => $page,
                "limit" => $limit,
                "total" => $total
            ]
        ]);
    }

    public function show(Request $req, Response $res) {
        $id = $req->params['id'];

        $lead = $this->repository->findById($id);
        if (!$lead) {
            $res->status(404);
            $res->json(["error" => "Not found"]);
            return;
        }

        if (!PolicyService::canView($req->user, $lead['user_id'])) {
            $res->status(403);
            $res->json(["error" => "Forbidden"]);
            return;
        }

        $res->status(200);
        $res->json($lead);
    }

    public function store(Request $req, Response $res) {
        $data = $req->body;

        $data['user_id'] = $req->user['sub'];

        $id = $this->repository->create($data);

        $res->status(201);
        $res->json(["id" => $id]);
    }

    public function update(Request $req, Response $res) {
        $id = $req->params['id'];
        $data = $req->body;

        $lead = $this->repository->findById($id);
        if (!$lead) {
            $res->status(404);
            $res->json(["error" => "Not found"]);
            return;
        }

        if (!PolicyService::canUpdate($req->user, $lead['user_id'])) {
            $res->status(403);
            $res->json(["error" => "Forbidden"]);
            return;
        }

        $updated = $this->repository->update($id, $data);

        $res->status(200);
        $res->json(["updated" => $updated]);
    }

    public function destroy(Request $req, Response $res) {
        $id = $req->params['id'];

        $lead = $this->repository->findById($id);
        if (!$lead) {
            $res->status(404);
            $res->json(["error" => "Not found"]);
            return;
        }

        if (!PolicyService::canDelete($req->user, $lead['user_id'])) {
            $res->status(403);
            $res->json(["error" => "Forbidden"]);
            return;
        }

        $deleted = $this->repository->delete($id);

        $res->status(200);
        $res->json(["deleted" => $deleted]);
    }
}
?>