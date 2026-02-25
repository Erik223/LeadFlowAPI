<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\UserRepository;
use App\Services\PolicyService;

class UserController {
    private UserRepository $repository;

    public function __construct() {
        $this->repository = new UserRepository();
    }

    public function index(Request $req, Response $res) {
        if (!PolicyService::canViewAny($req->user)) {
            $res->status(403);
            $res->json(["error" => "Forbidden"]);
            return;
        }

        $res->status(200);
        $res->json($this->repository->findAll());
    }

    public function show(Request $req, Response $res) {
        $id = $req->params['id'];

        if (!PolicyService::canView($req->user, $id)) {
            $res->status(403);
            $res->json(["error" => "Forbidden"]);
            return;
        }

        $user = $this->repository->findById($id);
        if (!$user) {
            $res->status(404);
            $res->json(["error" => "Not found"]);
            return;
        }

        $res->status(200);
        $res->json($user);
    }

    public function store(Request $req, Response $res) {
        $data = $req->body;

        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['password'] = $hash;

        $id = $this->repository->create($data);

        $res->status(201);
        $res->json(["id" => $id]);
    }

    public function update(Request $req, Response $res) {
        $id = $req->params['id'];
        $data = $req->body;

        if (!PolicyService::canUpdate($req->user, $id)) {
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

        if (!PolicyService::canDelete($req->user, $id)) {
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