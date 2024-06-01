<?php

declare(strict_types=1);

namespace App\Docs\Strategies;

trait HelperResponseFieldsTrait
{
    /**
     * @param array $data
     * @param array|null $keys
     * @param string|null $method
     * @return array
     * @throws \Exception
     */
    protected function withCheckKeys(array $data, ?array $keys = null, ?string $method = null)
    {
        // чтобы larastan не ругался
        /** @var mixed $var */
        $var = $this;
        if (is_null($keys)) {
            $keys = $var->transformerKeys();
        }

        $sortedKeys = array_keys($keys);
        sort($sortedKeys);

        $sortedData = array_keys($data);
        sort($sortedData);

        if ($sortedKeys !== $sortedData) {
            $message = 'Arrays is not equals in "' . __CLASS__ . '"';
            if (!is_null($method)) {
                $message .= ' from ' . $method;
            }
            $different = array_diff($sortedKeys, $sortedData);
            if (!empty($different)) {
                $message .= ' keys not found [' . implode(', ', $different) . ']';
            }
            throw new \Exception($message);
        }
        return $data;
    }
}
