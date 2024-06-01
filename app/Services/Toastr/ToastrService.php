<?php

declare(strict_types=1);

namespace App\Services\Toastr;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Session;

class ToastrService
{

    /**
     * Added notifications
     *
     * @var array
     */
    protected $notifications = [];

    /**
     * Illuminate Session
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Toastr config
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    private $allowedTypes = ['error', 'info', 'success', 'warning', 'primary'];
    private $allowedTitles = ['Error', 'Info', 'Success', 'Warning', 'Primary'];

    /**
     * ToastrService constructor.
     * @param \Illuminate\Session\Store $session
     * @param Repository $config
     */
    public function __construct(\Illuminate\Session\Store $session, Repository $config)
    {
        $this->session = $session;
        $this->config = $config;
    }

    /**
     * Set default languages title
     *
     * @param array $data
     * @return $this
     */
    public function config(array $data)
    {
        foreach ($this->allowedTypes as $key => $type) {
            if (isset($data['title'][$type])) {
                $this->allowedTitles[$key] = $data['title'][$type];
            }
        }
        return $this;
    }

    /**
     * Remove session
     */
    private function removeSession()
    {
        $this->session->remove('toastr::notifications');
    }

    /**
     * Return first notification to Array
     *
     * @return array
     */
    public function toArray()
    {
        $this->removeSession();

        $notification = array_shift($this->notifications);

        if (empty($notification['options'])) {
            unset($notification['options']);
        }
        return $notification;
    }

    /**
     * Return first notification to Json
     *
     * @return string
     */
    public function toJson()
    {
        $this->removeSession();

        $notification = array_shift($this->notifications);

        if (empty($notification['options'])) {
            unset($notification['options']);
        }
        return json_encode($notification);
    }

    /**
     * @return bool|string
     */
    public function render()
    {
        if (!$this->session->get('toastr::notifications')) {
            return false;
        }
        $notifications = $this->session->get('toastr::notifications');
        if (!$notifications) {
            $notifications = [];
        }

        $output = '<script>';
        $lastConfig = [];
        foreach ($notifications as $notification) {
            $config = $this->config->get('toastr.options');

            if (count($notification['options']) > 0) {
                // Merge user supplied options with default options
                $config = array_merge($config, $notification['options']);
            }

            // Config persists between toasts
            if ($config !== $lastConfig) {
                $output .= 'window.toastrOptions = ' . json_encode($config) . ';';
                $lastConfig = $config;
            }

            // Toastr output
            $output .= 'window.toastrNotification = ' . json_encode([
                'type' => $notification['type'],
                'title' => str_replace("'", "\\'", htmlentities($notification['title'])),
                'text' => str_replace("'", "\\'", str_replace(['&lt;', '&gt;'], ['<', '>'], e($notification['message']))),
            ]) . ';';
        }
        $output .= '</script>';

        return $output;
    }

    /**
     * Add a notification
     *
     * @param string $type Could be error, info, success, or warning.
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     *
     * @return bool Returns whether the notification was successfully added or
     * not.
     */
    private function add($type, $message, $title = null, $options = [])
    {
        if (!in_array($type, $this->allowedTypes)) {
            return false;
        }

        if (is_null($title)) {
            $title = $this->allowedTitles[array_search($type, $this->allowedTypes)];
        }
        $this->notifications[] = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'options' => $options
        ];

        $this->session->flash('toastr::notifications', $this->notifications);
        return true;
    }

    /**
     * @param string $message
     * @param null|string $title
     * @param array $options
     * @return $this
     */
    public function info($message, $title = null, $options = [])
    {
        $this->add('info', $message, $title, $options);

        return $this;
    }

    /**
     * Shortcut for adding an error notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     *
     * @return $this
     */
    public function error($message, $title = null, $options = [])
    {
        $this->add('error', $message, $title, $options);

        return $this;
    }

    /**
     * Shortcut for adding a warning notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     *
     * @return $this
     */
    public function warning($message, $title = null, $options = [])
    {
        $this->add('warning', $message, $title, $options);

        return $this;
    }

    /**
     * Shortcut for adding a success notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     *
     * @return $this
     */
    public function success($message, $title = null, $options = [])
    {
        $this->add('success', $message, $title, $options);

        return $this;
    }

    /**
     * Shortcut for adding a success notification
     *
     * @param string $message The notification's message
     * @param string $title The notification's title
     * @param array $options
     *
     * @return $this
     */
    public function primary($message, $title = null, $options = [])
    {
        $this->add('primary', $message, $title, $options);

        return $this;
    }

    /**
     * Clear all notifications
     *
     * @return $this
     */
    public function clear()
    {
        $this->notifications = [];

        return $this;
    }
}
