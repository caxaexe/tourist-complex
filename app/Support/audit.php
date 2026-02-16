<?php

use App\Models\AuditLog;

if (! function_exists('logAudit')) {
    function logAudit(
        string $action,
        ?object $entity = null,
        ?array $old = null,
        ?array $new = null
    ): void {
        try {
            $request = request();

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'entity_type' => $entity ? get_class($entity) : null,
                'entity_id' => $entity && isset($entity->id) ? (int)$entity->id : null,
                'old_values' => $old,
                'new_values' => $new,
                'ip' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'url' => $request?->fullUrl(),
                'method' => $request?->method(),
            ]);
        } catch (\Throwable $e) {
            \Log::error('AuditLog failed', [
                'message' => $e->getMessage(),
                'action' => $action ?? null,
                'entity' => $entity ? get_class($entity) : null,
            ]);
        }

    }
}
