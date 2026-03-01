<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\PolicyService;
use Throwable;

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

        $users = $this->repository->findAll();

        $all = [];
        foreach ($users as $user) { $all[] = $user->toArray(); }

        $res->status(200);
        $res->json($all);
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
        $res->json($user->toArray());
    }

    public function store(Request $req, Response $res) {
        try {
            $data = $req->body;

            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $data['password'] = $hash;

            $user = new User(
                name: $data['name'], 
                email: $data['email'], 
                passwordHash: $data['password']
            );

            $id = $this->repository->create($user);

            $res->status(201);
            $res->json(["id" => $id]);
        }
        catch (Throwable $error) {
            $res->status(422);
            $res->json(["error" => $error->getMessage()]);
        }
    }

    public function update(Request $req, Response $res) {
        try {
            $id = $req->params['id'];
            $data = $req->body;

            $user = $this->repository->findById($id);
            if (!$user) {
            $res->status(404);
            $res->json(["error" => "Not found"]);
            return;
            }

            if (!PolicyService::canUpdate($req->user, $id)) {
                $res->status(403);
                $res->json(["error" => "Forbidden"]);
                return;
            }

            $updatedUser = $user->copy(
                name: $data['name'] ?? null, 
                email: $data['email'] ?? null, 
                passwordHash: $data['password'] ?? null
            );

            $updated = $this->repository->update($id, $updatedUser);

            $res->status(200);
            $res->json(["updated" => $updated]);
        }
        catch (Throwable $error) {
            $res->status(422);
            $res->json(["error" => $error->getMessage()]);
        }
    }

    public function destroy(Request $req, Response $res) {
        $id = $req->params['id'];
        
        $user = $this->repository->findById($id);
        if (!$user) {
            $res->status(404);
            $res->json(["error" => "Not found"]);
            return;
        }

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