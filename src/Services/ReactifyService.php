<?php

namespace PHPDominicana\Reactify\Services;

use PHPDominicana\Reactify\Models\ReactifyTable as Reaction;
class ReactifyService
{
    public function react($userId, $reactableType, $reactableId, $type)
    {
        $reaction = new Reaction([
            'user_id' => $userId,
            'type' => $type
        ]);
        $reactable = $reactableType::findOrFail($reactableId);
        $reactable->reactions()->save($reaction);
    }

    public function getReactionCount($reactableType, $reactableId)
    {
        $reactable = $reactableType::findOrFail($reactableId);
        return $reactable->reactions()->count();
    }

    public function getReactionCountByType($reactableType, $reactableId, $type)
    {
        $reactable = $reactableType::findOrFail($reactableId);
        return $reactable->reactions()->where('type', $type)->count();
    }
}
