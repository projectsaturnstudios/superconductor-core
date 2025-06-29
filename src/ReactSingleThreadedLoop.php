<?php

namespace Superconductor;

use Illuminate\Support\Facades\Log;
use React\EventLoop\Loop;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use Illuminate\Support\Facades\Cache;

class ReactSingleThreadedLoop //extends LoopAbstract
{
    protected float $sleep_time;
    protected ?Loop $loop_thread = null;
    protected ?Deferred $promise_resolver = null;
    protected ?PromiseInterface $stop_promise = null;

    public function __construct(protected string $loop_id, ?int $nap_for_how_long = null,)
    {
        $this->sleep_time = ($nap_for_how_long ?? config('mcp.sessions.loop_time', 100000)) * 0.000001;
    }

    protected function stop(): void
    {
        $this->promise_resolver->resolve(null);
        Cache::forget("stop-loop-{$this->loop_id}");
    }

    protected function listen_for_stop(): void
    {
        $this->stop_promise = ($this->promise_resolver = (new Deferred))->promise()->then(fn() => $this->loop_thread->stop());
        $this->loop_thread->addPeriodicTimer($this->sleep_time, fn() => Cache::get("stop-loop-{$this->loop_id}") ? $this->stop() : null);
    }

    public function start(?callable $callback = null) : void
    {
        Log::debug("Starting loop {$this->loop_id} in ReactSingleThreadedLoop");
        $this->loop_thread = new Loop();
        $this->listen_for_stop();
        $this->loop($callback);
    }

    protected function loop(?callable $callback = null): void
    {
        $this->loop_thread->addPeriodicTimer($this->sleep_time, fn()  => ($callback) ?  $callback() : null );
        $this->loop_thread->run();
    }
}
