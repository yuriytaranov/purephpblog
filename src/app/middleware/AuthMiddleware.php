<?php

namespace app\middleware;

trait AuthMiddleware
{
    public function isAuthenticated(): bool {
        return app()->sessionGet("is_authenticated", false);
    }
}