<?php
namespace App\Controllers\Middleware;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MainModel;

class User implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 미들웨어 이전 처리 작업
        $user = new MainModel();
        $user->user();
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // 미들웨어 이후 처리 작업
        return $response;
    }
}