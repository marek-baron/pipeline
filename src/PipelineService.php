<?php

declare(strict_types=1);

namespace Baron\Pipeline;

use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

/**
 * Class PipelineService
 *
 * @template T
 * @template P of \Baron\Pipeline\PipelineInterface
 * Create Pipeline by config array
 * @package Baron\Pipeline
 * @author Marek Baron<baron.marek@googlemail.com>
 */
class PipelineService
{

    /**
     * Creates Pipeline by array and configuration
     *
     * @param array<TaskInterface<T>|array<TaskInterface<T>>> $tasks
     * @param class-string<P> $classFqn
     * @param ?array<mixed> $options
     *
     * @return P
     */
    public function create(array $tasks, string $classFqn = Pipeline::class, array $options = null): PipelineInterface
    {
        $interfaces = class_implements($classFqn);
        if (!class_exists($classFqn) || !$interfaces || !in_array(PipelineInterface::class, $interfaces, true)) {
            throw new RuntimeException("$classFqn does not implement \\Baron\\Pipeline\\PipelineInterface");
        }

        $pipeline = new $classFqn(self::createRecursive($tasks));
        $pipeline->setOptions($options ?? []);

        return $pipeline;
    }

    /**
     * Uses a psr-11 Container (=Zend ServiceManager, =Laravel Serivemanager etc) to create all tasks
     *
     * @param ContainerInterface $serviceContainer
     * @param array<class-string<TaskInterface<T>>|TaskInterface<T>|array<class-string<TaskInterface<T>>|TaskInterface<T>>> $tasks
     * @param class-string<P> $classFqn
     * @param array<mixed>|null $options
     *
     * @return P
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createPsr11(
        ContainerInterface $serviceContainer,
        array $tasks,
        string $classFqn = Pipeline::class,
        array $options = null
    ): PipelineInterface {
        array_walk_recursive(
            $tasks,
            static function (&$value) use ($serviceContainer) {
                if (is_string($value)) {
                    $value = $serviceContainer->get($value);
                }
            }
        );
        /** @var array<TaskInterface<T>|array<TaskInterface<T>>> $tasks php stan does not understand this replaces the strings by its class equivalent */
        return self::create($tasks, $classFqn, $options ?? []);
    }

    /**
     * Recursively transform task array to valid pipeline
     *
     * @param array<TaskInterface<T>|array<TaskInterface<T>>> $tasks
     * @return array<TaskInterface<T>>
     */
    private function createRecursive(array $tasks): array
    {
        foreach ($tasks as $i => $task) {
            if (is_array($task)) {
                $task = new RecursivePipeline(self::createRecursive($task));
            }
            if (!$task instanceof TaskInterface) {
                throw new InvalidArgumentException('A task does not implement Baron\Pipeline\TaskInterface');
            }
            $tasks[$i] = $task;
        }
        /** @var array<TaskInterface<T>> $tasks */
        return $tasks;
    }

}
