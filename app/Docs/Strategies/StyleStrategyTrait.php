<?php

declare(strict_types=1);

namespace App\Docs\Strategies;

trait StyleStrategyTrait
{
    /**
     * Необходимо для создания уровня, например когда в списке еще один список
     *
     * @var int
     */
    private $position = 0;

    /**
     * @param array $data
     * @param string|null $description
     * @param bool $simple
     * @return string
     */
    protected function listFields(array $data, ?string $description = null, bool $simple = true)
    {
        $output = '';
        if (!is_null($description)) {
            $output .= $description . "<br>";
        } else {
            $output = "\n";
        }
        if ($simple) {
            $output .= '<ul>';
            foreach ($data as $key => $value) {
                if ($this->position > 0) {
                    //$output = $this->getOutputPosition($output, $data, $key);
                }
                if (is_array($value)) {
                    $output .= '<li><code>' . $key . '</code>&nbsp;<small>' . $value[0] . '</small><br>' . $value[1] . '</li>';
                } else {
                    $output .= '<li><code>' . $key . '</code> ' . $value . '</li>';
                }
            }
            $output .= '</ul>';
            //$this->positionDown();
            return $output;
        } else {
            $output .= '<ul>';
            foreach ($data as $key => $value) {
                $item = '<li><code>' . $key . '</code>&nbsp;<small>' . $value['type'] . '</small>';
                if (!is_null($value['description'])) {
                    $item .= '<br>' . $value['description'];
                }
                $item .= '</li>';
                $output .= $item;
            }
            $output .= '</ul>';
            return $output;
        }
    }

    /**
     * @param array $data
     * @param string|null $description
     * @param bool $simple
     * @return string
     */
    protected function listFieldsOld(array $data, ?string $description = null, bool $simple = true)
    {
        $output = '';
        if (!is_null($description)) {
            $output .= $description . "<br>";
        } else {
            $output = "\n";
        }
        if ($simple) {
            foreach ($data as $key => $value) {
                if ($this->position > 0) {
                    $output = $this->getOutputPosition($output, $data, $key);
                }
                if (is_array($value)) {
                    $output .= '• **`' . $key . '`** &nbsp;<small>' . $value[0] . '</small> <br> &nbsp;&nbsp;&nbsp;' . $value[1] . ' <br>';
                } else {
                    $output .= '• **`' . $key . '`** - ' . $value . ' <br>';
                }
            }
            $this->positionDown();
            return $output;
        } else {
            foreach ($data as $key => $value) {
                $item = '• **`' . $key . '`** &nbsp;<small>' . $value['type'] . '</small>';
                if (!is_null($value['description'])) {
                    $item .= '<br> &nbsp;&nbsp;&nbsp;' . $value['description'];
                }
                $item .= '<br>';
                $output .= $item;
            }
            return $output;
        }
    }

    /**
     * @param string $output
     * @param array $data
     * @param string $key
     * @return string
     */
    private function getOutputPosition(string $output, array $data, string $key)
    {
        $keyP = array_search($key, array_keys($data));
        if ($keyP !== false && $keyP !== 0) {
            $output .= '&nbsp;&nbsp;&ensp;';
        }
        return $output;
    }

    /**
     *
     */
    protected function positionUp()
    {
        $this->position++;
    }

    /**
     *
     */
    protected function positionDown()
    {
        if ($this->position !== 0) {
            $this->position--;
        }
    }
}
