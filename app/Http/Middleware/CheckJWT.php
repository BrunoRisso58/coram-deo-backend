<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Tymon\JWTAuth\Exceptions;
use Illuminate\Http\JsonResponse;
use App\Repo\UserRepository;
use App\Traits\ApiResponse;

class CheckJWT
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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!$user = $this->userRepository->getAuthenticatedUser()) {
                return $this->errorResponse('User not found', ResponseStatus::HTTP_NOT_FOUND);
            }
        } catch (Exceptions\TokenExpiredException $e) {
            return $this->errorResponse('Token has expired', ResponseStatus::HTTP_UNAUTHORIZED);
        } catch (Exceptions\TokenInvalidException $e) {
            return $this->errorResponse('Token is invalid', ResponseStatus::HTTP_UNAUTHORIZED);
        } catch (Exceptions\JWTException $e) {
            return $this->errorResponse('Token is missing', ResponseStatus::HTTP_UNAUTHORIZED);
        }

        $request->merge(['user' => $user]);

        return $next($request);
    }
}
