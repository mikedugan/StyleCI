@extends(Config::get('graham-campbell/core::layout'))

@section('title')
Repos
@stop

@section('top')
<div class="page-heading">
    <div class="container">
        <h1>Analysed Repos</h1>
        <p>Here you can see all our analysed repos.</p>
    </div>
</div>
@stop

@section('content')
@forelse($repos as $repo)
<div class="row">
    <div class="col-sm-8">
    <h3>{{ $repo->name }}</h3>
    @if ($commit = $repo->commits()->where('ref', 'refs/heads/master')->orderBy('created_at', 'desc')->first())
        @if ($commit->status() === 1)
        <p style="color:green">
        @elseif ($commit->status() === 2)
        <p style="color:red">
        @else
        <p style="color:grey">
        @endif
            <strong>{{ $commit->summary() }}</strong>
        </p>
        </div>
        <div class="col-sm-4 text-right list-vcenter">
            <a class="btn btn-primary" href="{{ route('show-repo', $repo->id) }}"><i class="fa fa-history"></i> Show Commits</a>
        </div>
    @else
        <p><strong>No commits have been pushed to the master yet.</strong></p>
        </div>
    @endif
</div>
<hr>
@empty
We haven't analysed anything yet.
@endforelse
@stop
