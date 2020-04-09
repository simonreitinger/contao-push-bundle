<?php

namespace SimonReitinger\ContaoPushBundle\Model;

use Contao\Model;

/**
 * @property string $id
 * @property string $endpoint
 * @property string $publicKey
 * @property string $contentEncoding
 * @property string $authToken
 */
class PushModel extends Model
{
    protected static $strTable = 'tl_push';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    public function setPublicKey(string $publicKey): self
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    public function setAuthToken(string $authToken): self
    {
        $this->authToken = $authToken;

        return $this;
    }

    public function getContentEncoding(): ?string
    {
        return $this->contentEncoding;
    }

    public function setContentEncoding(string $contentEncoding): self
    {
        $this->contentEncoding = $contentEncoding;

        return $this;
    }
}
