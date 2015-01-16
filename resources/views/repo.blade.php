@extends(Config::get('graham-campbell/core::layout'))

@section('title')
{{ $repo->name }}
@stop

@section('top')
<div class="page-heading">
    <div class="container">
        <h1>{{ $repo->name }}</h1>
        <p>Here you can see all the analysed commits</p>
    </div>
</div>
@stop

@section('content')
<div class="table-responsive">
    <table class="table">
        <tr>
            <th>Commit</th>
            <th>Branch</th>
            <th>Time</th>
            <th>Status</th>
            <th></th>
        </tr>
    @forelse($commits as $commit)
        <tr
        @if ($commit->status() === 1)
        class="success"
        @elseif ($commit->status() === 2)
        class="danger"
        @else
        class="active"
        @endif
        >
            <td>
                <strong>{{ $commit->message }}</strong>
                <br>
                <small>{{ $commit->created_at->diffForHumans() }}</small>
            </td>
            <td>{{ $commit->ref }}</td>
            <td><small>{{ $commit->excecutedTime() }}</small></td>
            <td>
                @if ($commit->status() === 1)
                <p style="color:green">
                @elseif ($commit->status() === 2)
                <p style="color:red">
                @else
                <p style="color:grey">
                @endif
                <strong>{{ $commit->summary() }}</strong>
                </p>
            </td>
            <td align="right">
                <a href="{{ route('commit_path', $commit->id) }}"><span class="badge-id">{{ $commit->shorthandId() }}</span></a>
                <a class="btn btn-sm btn-default" href="{{ route('commit_path', $commit->id) }}">Show Details</a>
            </td>
        </tr>
    @empty
        <p class="lead">We haven't analysed anything yet.</p>
    @endforelse
    </table>
</div>
@stop
