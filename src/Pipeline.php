<?php

declare(strict_types=1);

namespace Baron\Pipeline;

/**
 * Class Pipeline
 *
 * Classic pipeline
 *
 * @template T
 * @implements PipelineInterface<T>
 *
 * @package Baron\Pipeline
 * @author  Marek Baron<baron.marek@googlemail.com>
 */
class Pipeline implements PipelineInterface
{
    /** @use PipelineTrait<T> */
    use PipelineTrait;
}
