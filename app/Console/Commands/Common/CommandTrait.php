<?php

declare(strict_types=1);

namespace App\Console\Commands\Common;

use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Console\Concerns\InteractsWithIO;

trait CommandTrait
{
    public $start = null;

    /**
     * @var bool
     */
    public $testing = false;

    /**
     * Вывод в консоли и запись в лог
     *
     * @param string $message
     * @param bool $log
     * @param bool $info
     */
    public function log(string $message, $log = true, $info = true): void
    {
        if ($info) {
            $this->info($message);
        }
        if ($this->log && $log && !empty($this->logger)) {
            info($message);
        }
        if (isset($this->logger)) {
            $this->logger->info($message);
        }
    }

    /**
     * @param string $message
     */
    public function infoOnly(string $message)
    {
        if ($this->output instanceof \Illuminate\Console\OutputStyle) {
            $this->info($message);
        }
    }

    /**
     * @param string|array $message
     */
    public function errorLog($message): void
    {
        $this->line($this->getErrorLine());
        $this->check($message, function ($text) {
            return $this->redText($text);
        });
    }

    /**
     * @param string|array $message
     */
    public function successLog($message): void
    {
        $this->line($this->getSuccessLine());
        $this->check($message, function ($text) {
            return $this->greenText($text);
        });
    }

    /**
     * @param string|array $message
     * @param mixed $function
     * @return bool
     */
    private function check($message, $function): bool
    {
        if (!is_array($message)) {
            $this->info($function($message));
            return true;
        }
        foreach ($message as $text) {
            if (!is_array($text)) {
                $this->info($function($text));
                continue;
            }
            foreach ($text as $value) {
                $this->info($function($value));
            }
        }
        return true;
    }

    /**
     * Присвоить конкретные флаги
     *
     */
    public function setOptions(): void
    {
        foreach (get_object_vars($this) as $key => $var) {
            if (!$this->hasOption($key)) {
                continue;
            }
            if (is_bool($var)) {
                $value = $this->option($key);
                if ($value === 'true') {
                    $this->{$key} = true;
                } elseif ($value === 'false') {
                    $this->{$key} = false;
                } else {
                    $this->{$key} = $this->option($key);
                }
            } else {
                if (!empty($this->option($key))) {
                    $value = $this->option($key);
                    if ($value === 'true') {
                        $this->{$key} = true;
                    } elseif ($value === 'false') {
                        $this->{$key} = false;
                    } else {
                        $this->{$key} = $this->option($key);
                    }
                }
            }
        }
    }

    /**
     * До выполнения скрипта
     */
    public function start(): void
    {
        $this->setOptions();

        $this->log('------------------- ' . __CLASS__ . ' -------------------');

        $this->start = microtime(true);
    }

    /**
     * После выполнения скрипта
     * @param array $messages
     */
    public function finish($messages = []): void
    {
        $time = microtime(true) - $this->start;

        if ($this->log) {
            $this->log('Time: ' . (string)gmdate("H:i:s", (int)$time) . '.');
        } else {
            $this->log("\n" . 'Time: ' . (string)gmdate("H:i:s", (int)$time) . '.');
        }

        if (!empty($messages)) {
            foreach ($messages as $message) {
                $this->log($message);
            }
        }

        $this->log('------------------- ' . __CLASS__ . ' -------------------');
    }

    /**
     * Создание прогресс бара
     *
     * $bar = $this->bar(count([]))
     * $bar->advance();
     * $bar->finish();
     *
     * @param int $count
     * @param bool $showCount
     * @param string|null $title
     * @return ProgressBar
     */
    public function bar(int $count, $showCount = true, ?string $title = null): ProgressBar
    {
        ProgressBar::setFormatDefinition('custom', ' %current%/%max% %bar%');

        if ($showCount) {

            if (!is_null($title)) {
                $this->log($title . ' count: ' . $count);
            } else {
                $this->log('Count: ' . $count);
            }
        }
        $bar = $this->output->createProgressBar($count);
        $bar->setFormat('normal');
        //$bar->setFormat('Progress: %percent%%');
        return $bar;
    }

    /**
     * @param string $message
     * @return string
     */
    public function redText(string $message): string
    {
        return "\e[0;31m" . $message . "\e[0;31m";
    }

    /**
     * @param string $message
     * @return string
     */
    public function greenText(string $message): string
    {
        return "\e[0;32m" . $message . "\e[0;32m";
    }

    /**
     * @return string
     */
    public function getErrorLine(): string
    {
        return $this->redText($this->errorText());
    }

    /**
     * @return string
     */
    public function getSuccessLine(): string
    {
        return $this->greenText($this->successText());
    }

    /**
     * Текст ошибки
     *
     * @return string
     */
    private function errorText(): string
    {
        return 'ERROR!';
    }

    /**
     * Текст успешности
     *
     * @return string
     */
    private function successText(): string
    {
        return 'SUCCESS!';
    }

    /**
     *
     */
    public function setTesting(): void
    {
        $this->testing = true;
    }
}
