<?php

namespace PHPDominicana\Reactify\Traits;

use PHPDominicana\Reactify\Enums\Reaction;
use PHPDominicana\Reactify\Models\ReactifyTable;

trait HasReaction
{
    public function reactify()
    {
        return $this->morphMany(ReactifyTable::class, 'reactionable');
    }

    public function react(string $userId, Reaction $type): void
    {
        $this->reactify()->create([
            'user_id' => $userId,
            'type' => $type
        ]);

        $this->incrementReactCount($userId, $type);
    }

    public function unreact(string $userId, Reaction $type): void
    {
        $this->reactify()->where('user_id', $userId)->where('type', $type)->delete();

        $this->decrementReactCount($userId, $type);
    }

    public function toggleReact(string $userId, Reaction $type)
    {
        if ($this->isReactedBy($userId, $type)) {
            $this->unreact($userId, $type);
        } else {
            $this->react($userId, $type);
        }
    }

    public function isReactedBy(string $userId, Reaction $type)
    {
        return $this->reactify()->where('user_id', $userId)->where('type', $type)->exists();
    }

    /**
     * Private. Increment the total like count stored in the counter
     */
    private function incrementReactCount(string $userId, Reaction $reaction): void
    {
        $counter = $this->reactify()->where('user_id', $userId)->where('type', $reaction)->first()?->reactionCounter;
        if ($counter) {
            $counter->count++;
            $counter->save();
        }
    }

    /**
     * Private. Decrement the total like count stored in the counter
     */
    private function decrementReactCount(string $userId, Reaction $reaction): void
    {
        $counter = $this->reactify()->where('user_id', $userId)->where('type', $reaction)->first()?->reactionCounter;
        if ($counter) {
            $counter->count--;
            $counter->save();
        }
    }

    /**
     * Populate the $model->reacts attribute
     */
    public function getReactCountAttribute()
    {
        return $this->reactionCounter ? $this->reactionCounter->count : 0;
    }

    /**
     * Fetch records that are reacted by a given user.
     * Ex: Book::whereReactedBy(Reaction::like, 123)->get();
     * @access private
     */
    public function scopeWhereReactedBy($query, Reaction $reaction, ?string $userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        return $query->whereHas('reactify', function ($q) use ($userId, $reaction) {
            $q->where('user_id', '=', $userId)
              ->where('type', '=', $reaction);
        });
    }

    /**
     * Fetch the primary ID of the currently logged in user
     * @return string
     */
    private function loggedInUserId(): string
    {
        return auth()->id();
    }

    /**
     * Get the count of a specific reaction type
     *
     * @param Reaction $reaction
     * @return int
     */
    public function reactions(Reaction $reaction): int
    {
        return $this->reactify()->where('type', $reaction)->count();
    }
}
