<?php
declare(strict_types=1);

namespace App\Util;

/**
 * Class MessageIdValidator
 */
class ConversationIdValidator
{
    /**
     * Regex for is UUID4.
     * @param string $messageId
     * @return bool
     */
    public function validate(string $messageId): bool
    {
        if (preg_match('/^([a-z|A-Z|0-9|-]){5,36}$/', $messageId)) {
            return true;
        }

        return false;
    }
}
