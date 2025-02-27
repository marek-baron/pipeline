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
trait PipelineTrait
{

    /** @var TaskInterface<T>[] */
    private array $tasks;
    /** @var array<mixed> */
    private array $options;

    /**
     * DefaultPipelineTrait constructor.
     *
     * @param TaskInterface<T>[] $tasks Array of tasks
     */
    public function __construct(array $tasks = [])
    {
        $this->tasks = $tasks;
    }

    /**
     * Adds a new Task to the pipeline
     *
     * @param TaskInterface<T> $task Task to be added
     *
     * @return void
     */
    public function add(TaskInterface $task): void
    {
        $this->tasks[] = $task;
    }

    /**
     * Shifts the current task from the pipeline, and removes it from execution
     *
     * @return TaskInterface<T>|null
     */
    public function next(): ?TaskInterface
    {
        return array_shift($this->tasks);
    }

    /**
     * Set Config for your pipeline, which is accessible from your tasks
     *
     * @param array<mixed> $options
     *
     * @return void
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * Returns configuration for your pipeline (exp env variables?)
     *
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Start execution of all tasks within the pipeline
     *
     * @param PayloadInterface $payload Payload to be passed through all tasks
     *
     * @return PayloadInterface
     * @phpstan-return PayloadInterface
     */
    public function handle(PayloadInterface $payload): PayloadInterface
    {
        $task = $this->next();

        if ($task === null) {
            return $payload;
        }

        try {
            $payload = $task($payload, $this);
        } finally {
            array_unshift($this->tasks, $task);
        }

        return $payload;
    }
}
