<?php
namespace Jmarcos16\MiniRouter;

use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class Request extends HttpFoundationRequest
{

    /**
     * Create a new request instance.
     *
     * @return static
     */
    public static function capture(): Request
    {
        return static::createFromBase(HttpFoundationRequest::createFromGlobals());
    }

    /**
     * This method belongs to Symfony HttpFoundation.
     *
     * Instead, you may use the "input" method.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    #[\Override]
    public function get(string $key, mixed $default = null): mixed
    {
        return parent::get($key, $default);
    }

    /**
     * Get all the input data.
     *
     * @return array
     */
    public function all(): array
    {
        return array_merge($this->query->all(), $this->request->all());
    }

    /**
     * Get the request URI.
     *
     * @return string
     */
    public function uri(): string
    {
        return rtrim(preg_replace('/\?.*/', '', $this->getRequestUri()), '/');
    }

    /**
     * Get the full URL.
     *
     * @return string
     */
    public function fullUrl(): string
    {
        return $this->getUri();
    }

    /**
     * Get the request scheme.
     *
     * @return string
     */
    public function scheme(): string
    {
        return $this->getScheme();
    }

    /**
     * Get the host.
     *
     * @return string
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->get($key, $default);
    }

    /**
     * Return the request instance.
     * 
     * @return Request
     */
    public function instance(): Request
    {
        return $this;
    }

    /**
     * Get the request content type.
     *
     * @return string
     */
    public function contentType(): string
    {
        return $this->headers->get('Content-Type');
    }

    /**
     * Return all the input data.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->all();
    }


    /**
     * Create a new request instance from the base request.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return static
     */
    public static function createFromBase(HttpFoundationRequest $request): Request
    {
        $instance = new static(
            $request->query->all(), $request->request->all(), $request->attributes->all(),
            $request->cookies->all(), (new static)->filterFiles($request->files->all()) ?? [], $request->server->all()
        );

        $instance->content = $request->content;
        $instance->request = $request->request;

        return $instance;
    }

    /**
     * Filter the given array of files, removing any empty values.
     *
     * @param  mixed  $files
     * @return mixed
     */
    protected function filterFiles($files)
    {
        if (! $files) {
            return;
        }

        foreach ($files as $key => $file) {
            if (is_array($file)) {
                $files[$key] = $this->filterFiles($files[$key]);
            }

            if (empty($files[$key])) {
                unset($files[$key]);
            }
        }

        return $files;
    }


}
