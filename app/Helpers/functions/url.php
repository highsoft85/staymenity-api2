<?php

declare(strict_types=1);

if (!function_exists('getUrl')) {
    /**
     * @param string $url
     * @param array $data
     * @return string
     */
    function getUrl(string $url, array $data = []): string
    {
        if (!empty($data)) {
            $parameters = [];
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $k1 => $v1) {
                                $parameters[] = $key . '[' . $k . ']' . '[' . $k1 . ']' . '=' . $v1;
                            }
                        } else {
                            $parameters[] = $key . '[' . $k . ']' . '=' . $v;
                        }
                    }
                } else {
                    if (is_bool($value)) {
                        $value = $value ? 'true' : 'false';
                    }
                    $parameters[] = $key . '=' . $value;
                }
            }
            $url .= '?' . implode('&', $parameters);
        }
        return $url;
    }
}
