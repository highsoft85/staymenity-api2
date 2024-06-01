<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;
use Dingo\Api\Contract\Debug\MessageBagErrors;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ResourceExceptionValidation
 * @package App\Exceptions
 *
 * Чтобы формат выл
 * не password => [0 => '']
 * а такой password => ''
 */
class ResourceExceptionValidation extends HttpException implements MessageBagErrors
{
    /**
     * MessageBag errors.
     *
     * @var MessageBag|array
     */
    protected $errors;

    /**
     * @var array
     */
    protected $aErrors = [];

    /**
     * Create a new resource exception instance.
     *
     * @param string $message
     * @param MessageBag|array $errors
     * @param Exception $previous
     * @param array $headers
     * @param int $code
     *
     * @return void
     */
    public function __construct($message = null, $errors = null, Exception $previous = null, $headers = [], $code = 0)
    {
        $this->aErrors = $errors;
        parent::__construct(422, $message, $previous, $headers, $code);
    }

    /**
     * Get the errors message bag.
     *
     * @return MessageBag|array
     */
    public function getErrors()
    {
        return $this->aErrors;
    }

    /**
     * Determine if message bag has any errors.
     *
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->aErrors);
    }
}
