<?php

namespace App\Services\Project;

interface ProjectServiceInterface
{
  public function getAll();
  public function create($request);
  public function update($request, $id);
  public function delete($id);
}