<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        // If already a JsonResponse, format it uniformly
        if ($response instanceof JsonResponse) {
            $original = $response->getData(true);

            // Preserve validation errors if they exist
            $errors = $original['errors'] ?? null;

            return response()->json([
                'code' => $response->getStatusCode(),
                'status' => false,
                'message' => $original['message'] ?? 'Request completed',
                'errors' => $errors, // Include errors if present
                'data' => $original['data'] ?? [],
            ], $response->getStatusCode());
        }

        // Handle non-JSON responses (unchanged)
        $statusCode = $response->getStatusCode();
        $content = $response->getContent();

        if (empty($content)) {
            return response()->json([
                'code' => $statusCode,
                'status' => false,
                'message' => 'No Content',
                'data' => null,
            ], $statusCode);
        }

        if (str_starts_with($response->headers->get('Content-Type'), 'text/html')) {
            return response()->json([
                'code' => $statusCode,
                'status' => false,
                'message' => $statusCode === 404 ? 'Resource not found' : 'An error occurred',
                'data' => null,
            ], $statusCode);
        }

        return response()->json([
            'code' => $statusCode,
            'status' => false,
            'message' => 'Non-JSON response',
            'data' => null,
        ], $statusCode);
    }
}
