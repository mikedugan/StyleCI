@extends(Config::get('core.default'))

@section('title', 'Commit - '.$commit->message)
@section('description', $commit->message)

@section('top')
<div class="page-heading">
    <div class="container">
        <h1>{{ $commit->repo->name }} &mdash; Commit Analysis</h1>
        <p>Here you can see the results of the analysed commit.</p>
    </div>
</div>
@stop

@section('content')
<div class="commit">
    @if ($commit->status === 1)
    <p class="lead" style="color:green">
    @elseif ($commit->status === 2)
    <p class="lead" style="color:red">
    @else
    <p class="lead" style="color:grey">
    @endif
        {{ $commit->description() }}
    </p>
    <hr>
    <div class="row">
        <div class="col-sm-8">
            <h2>{{ $commit->message }}</h2>
            {{ $commit->created_at->diffForHumans() }}
            <h5>{{ $commit->id }}</h5>
        </div>
        <div class="col-sm-4">
            @if ($commit->status === 2)
            <ul class="list-group">
                <a class="list-group-item" href="{{ route('commit_download_path', $commit->id) }}">
                    <i class="fa fa-cloud-download"></i> Download patch
                </a>
                <a class="list-group-item" href="{{ route('commit_diff_path', $commit->id) }}">
                    <i class="fa fa-code"></i> Open diff file
                </a>
            </ul>
            @endif
        </div>
    </div>
    @if ($commit->status === 2)
    <hr>
    <pre class="brush: diff">
        {{ $commit->diff }}
    </pre>
    @endif
</div>
@stop

@section('bottom')
<script type="text/javascript">
    SyntaxHighlighter.defaults['toolbar'] = false;
    SyntaxHighlighter.defaults['gutter'] = false;
    SyntaxHighlighter.all();
</script>
@stop
