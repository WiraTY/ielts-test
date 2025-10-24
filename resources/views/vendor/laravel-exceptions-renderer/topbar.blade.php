<div style="background: rgba(15, 23, 42, 0.1); padding: 0.75rem 1rem; margin-bottom: 1rem; border-radius: 0.5rem; font-size: 0.9rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <strong>Exception Details:</strong> {{ $exception->class() }}
        </div>
        <div>
            <strong>File:</strong> {{ $exception->file() }}:{{ $exception->line() }}
        </div>
        @if($exception->request())
            <div>
                <strong>Request:</strong> {{ $exception->request()->method() }} {{ $exception->request()->path() }}
            </div>
        @endif
    </div>
</div>