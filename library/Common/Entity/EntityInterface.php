<?php

namespace Common\Entity;

interface EntityInterface
{
    /**
     * Get user identifier
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Set user identifier
     * Fluent interface
     * @param int $identifier
     * @return self
     */
    public function setId(int $identifier);
}
