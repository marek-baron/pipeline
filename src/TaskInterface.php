<?php

declare(strict_types=1);

namespace Baron\Pipeline;

/**
 * Interface TaskInterface
 *
 * @template T
 *
 * @package Baron\Pipeline
 * @author  Marek Baron<baron.marek@googlemail.com>
 */
interface TaskInterface
{

    /**
     * Action or single Task to be done in this step
     *
     * @param PayloadInterface $payload Payload containing all Information necessary for this action
     * @param PipelineInterface<T> $pipeline Pipeline currently executed
     *
     * @return PayloadInterface
     */
    public function __invoke(PayloadInterface $payload, PipelineInterface $pipeline): PayloadInterface;
}
