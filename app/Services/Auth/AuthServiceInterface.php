<?php

namespace App\Services\Auth;

interface AuthServiceInterface
{
  public function register($request);
  public function login($request);
  public function logout();
}