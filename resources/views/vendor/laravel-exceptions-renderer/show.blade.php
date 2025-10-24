<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exception->title() }}</title>
    <style>
        :root {
            color-scheme: light dark;
        }

        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f8fafc;
            color: #0f172a;
            line-height: 1.5;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background: #0f172a;
                color: #f8fafc;
            }
        }

        main {
            max-width: 960px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }

        header {
            margin-bottom: 2rem;
        }

        h1 {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            margin-bottom: 0.5rem;
        }

        h2 {
            margin-top: 2.5rem;
            font-size: 1.15rem;
        }

        .panel {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 20px 45px 0 rgba(15, 23, 42, 0.12);
        }

        @media (prefers-color-scheme: dark) {
            .panel {
                background: rgba(15, 23, 42, 0.85);
                box-shadow: none;
                border: 1px solid rgba(148, 163, 184, 0.2);
            }
        }

        .meta-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            margin-top: 1.5rem;
        }

        .meta-grid span {
            display: block;
            font-size: 0.85rem;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        @media (prefers-color-scheme: dark) {
            .meta-grid span {
                color: #cbd5f5;
            }
        }

        code, pre {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            background: rgba(15, 23, 42, 0.08);
            border-radius: 0.5rem;
            padding: 0.35rem 0.55rem;
            display: inline-block;
        }

        pre {
            white-space: pre-wrap;
            padding: 1rem;
            width: 100%;
            box-sizing: border-box;
        }

        ul, ol {
            padding-left: 1.25rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 0.95rem;
        }

        th, td {
            padding: 0.6rem 0.8rem;
            border: 1px solid rgba(148, 163, 184, 0.4);
            vertical-align: top;
        }

        th {
            text-align: left;
            background: rgba(148, 163, 184, 0.08);
        }

        .stack ol {
            margin: 0;
            padding-left: 1rem;
        }

        .stack li + li {
            margin-top: 0.4rem;
        }
    </style>
</head>
<body>
    <main>
        <header>
            <h1>{{ $exception->title() }}</h1>
            <div>{!! $exception->message() !!}</div>
            <div class="meta-grid">
                <div>
                    <span>Exception</span>
                    {{ $exception->class() }}
                </div>
                <div>
                    <span>PHP</span>
                    {{ PHP_VERSION }}
                </div>
                <div>
                    <span>Laravel</span>
                    {{ app()->version() }}
                </div>
                <div>
                    <span>Request</span>
                    {{ $exception->request()->method() }} {{ $exception->request()->fullUrl() }}
                </div>
            </div>
        </header>

        <section class="panel stack">
            <h2>Stack Trace</h2>
            <ol>
                @foreach ($exception->frames() as $index => $frame)
                    <li>
                        <strong>#{{ $index }}</strong>
                        {{ $frame->file() }}:{{ $frame->line() }}
                    </li>
                @endforeach
            </ol>
        </section>

        <section class="panel">
            <h2>Request Headers</h2>
            @php($headers = $exception->requestHeaders())
            @if ($headers)
                <table>
                    <tbody>
                        @foreach ($headers as $key => $value)
                            <tr>
                                <th>{{ $key }}</th>
                                <td>{!! $value !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No header data available.</p>
            @endif
        </section>

        <section class="panel">
            <h2>Route Context</h2>
            @php($routeContext = $exception->applicationRouteContext())
            @if ($routeContext)
                <table>
                    <tbody>
                        @foreach ($routeContext as $name => $value)
                            <tr>
                                <th>{{ $name }}</th>
                                <td>{!! $value !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No routing data available.</p>
            @endif
        </section>

        <section class="panel">
            <h2>Route Parameters</h2>
            @if ($routeParameters = $exception->applicationRouteParametersContext())
                <pre>{!! $routeParameters !!}</pre>
            @else
                <p>No route parameter data available.</p>
            @endif
        </section>

        <section class="panel">
            <h2>Database Queries</h2>
            @php($queries = $exception->applicationQueries())
            @if ($queries)
                <ol>
                    @foreach ($queries as ['connectionName' => $connectionName, 'sql' => $sql, 'time' => $time])
                        <li>
                            <strong>{{ $connectionName }}</strong> — {!! $sql !!} <em>({{ number_format($time, 2) }} ms)</em>
                        </li>
                    @endforeach
                </ol>
            @else
                <p>No database queries detected.</p>
            @endif
        </section>

        <section class="panel">
            <h2>Raw Markdown</h2>
            <pre>{{ $exceptionAsMarkdown }}</pre>
        </section>
    </main>
</body>
</html>
