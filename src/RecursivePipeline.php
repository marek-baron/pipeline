<?php

declare(strict_types=1);

namespace Baron\Pipeline;

/**
 * Class RecursivePipelineHandler
 *
 * Class to build up recursive pipelines, so you are able to create abstracted structure within a pipeline
 *
 * @template T
 * @extends Pipeline<T>
 * @implements TaskInterface<T>
 *
 * @package Baron\Pipeline
 * @author  Marek Baron<baron.marek@googlemail.com>
 */
class RecursivePipeline extends Pipeline implements TaskInterface
{

    /**
     * Execute internal pipe and pass it to the next handler
     *
     * @param PayloadInterface $payload Payload containing all Information necessary for this action
     * @param PipelineInterface<T> $pipeline Pipeline currently executed
     *
     * @return PayloadInterface
     */
    public function __invoke(PayloadInterface $payload, PipelineInterface $pipeline): PayloadInterface
    {
        $this->setOptions($pipeline->getOptions());
        return $pipeline->handle($this->handle($payload));
    }
}
