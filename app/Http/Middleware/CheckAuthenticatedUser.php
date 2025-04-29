<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Tymon\JWTAuth\Exceptions;
use Illuminate\Http\JsonResponse;
use App\Repo\UserRepository;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class CheckAuthenticatedUser
{
    use ApiResponse;

    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\JsonResponse)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            info('Request: ');
            info($request['user']->id);
            $authenticatedUser = $request['user'];
            $user = $this->userRepository->getUser($request->id);
        } catch (\Exception $e) {
            Log::error('Error in ' . __FILE__ . ' : ' . $e->getMessage());
            return $this->errorResponse('User not found', ResponseStatus::HTTP_BAD_REQUEST);
        }
        
        if ($authenticatedUser->role !== 'admin' && $authenticatedUser->email !== $user['email']) {
            return $this->errorResponse('Unauthorized access', ResponseStatus::HTTP_UNAUTHORIZED);
        }
        
        return $next($request);
    }
}
