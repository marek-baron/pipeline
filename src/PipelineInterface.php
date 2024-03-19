<?php

declare(strict_types=1);

namespace Baron\Pipeline;

/**
 * Interface PipelineInterface
 *
 * Contains a certain amount of tasks to be executed
 *
 * @template T
 *
 * @package Baron\Pipeline
 * @author  Marek Baron<baron.marek@googlemail.com>
 */
interface PipelineInterface
{

    /**
     * Adds a new Task to the pipeline
     *
     * @param TaskInterface<T> $task Task to be added
     *
     * @return void
     */
    public function add(TaskInterface $task): void;

    /**
     * Shifts the currect task from the pipeline, and removes it from execution
     *
     * @return TaskInterface<T>|null
     */
    public function next(): ?TaskInterface;

    /**
     * Start execution of all tasks within the pipeline
     *
     * @param PayloadInterface $payload Payload to be passed through all tasks
     *
     * @phpstan-return PayloadInterface
     * @return PayloadInterface
     */
    public function handle(PayloadInterface $payload): PayloadInterface;

    /**
     * Set Config for your pipeline, which is accessible from your tasks
     *
     * @param array<mixed> $options
     *
     * @return void
     */
    public function setOptions(array $options): void;

    /**
     * Returns configuration for your pipeline (exp env variables?)
     *
     * @return array<mixed>
     */
    public function getOptions(): array;
}
