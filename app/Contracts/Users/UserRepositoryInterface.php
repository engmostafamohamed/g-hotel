<?php
namespace App\Contracts\Users;

interface UserRepositoryInterface
{

	public function getAll();

    public function find($id);

    public function create($request);

    public function update($request, $id);

    public function delete($id);

}