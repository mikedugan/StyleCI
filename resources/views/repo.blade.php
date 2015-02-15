@extends(Config::get('core.default'))

@section('title', $repo->name)

@section('top')
<div class="page-heading">
    <div class="container">
        <h1>{{ $repo->name }}</h1>
        <p>Here you can see all the analysed commits</p>
    </div>
</div>
@stop

@section('content')
<a class="btn btn-lg btn-danger btn-circle btn-float pull-right js-analyse-repo" href="{{ route('repo_analyse_path', $repo->id) }}" data-id="{{ $repo->id }}" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>" data-toggle="tooltip" data-placement="left" title="Analyse Now">
    <i class="fa fa-undo"></i>
</a>
<div class="repo-table js-channel" data-channel="{{ $repo->id }}">
    <div class="repo-table-headers row hidden-xs @if($commits->count() == 0) hidden @endif">
        <div class="col-sm-6">
            <strong>Commit</strong>
        </div>
        <div class="col-sm-1">
            <strong>Time</strong>
        </div>
        <div class="col-sm-1">
            <strong>Status</strong>
        </div>
        <div class="col-sm-4">
            <!-- Actions -->
        </div>
    </div>
    <div class="commits">
        @forelse ($commits as $commit)
        <div id="js-commit-{{ $commit->shorthandId }}" class="row @if($commit->status === 1) bg-success @elseif ($commit->status === 2) bg-danger @else bg-active @endif">
            <div class="col-sm-6">
                <strong>{{ $commit->message }}</strong>
                <br>
                <small class="js-time-ago">{{ $commit->timeAgo }}</small>
            </div>
            <div class="col-sm-1">
                <small class="js-excecuted-time">{{ $commit->excecutedTime }}</small>
            </div>
            <div class="col-sm-1">
                <p class="js-status" style="@if ($commit->status === 1) color:green; @elseif ($commit->status === 2) color:red; @else color:grey; @endif">
                    <strong>{{ $commit->summary }}</strong>
                </p>
            </div>
            <div class="col-sm-4 repo-buttons">
                <a class="badge-id" href="https://github.com/{{ $repo->name }}/commit/{{ $commit->id }}">
                    {{ $commit->shorthandId }}
                </a>
                <a class="btn btn-sm btn-default" href="{{ route('commit_path', $commit->id) }}">Show Details</a>
            </div>
        </div>
        @empty
        <p class="lead">We haven't analysed anything yet.</p>
        @endforelse
    </div>
</div>
<div class="text-center">
    {!! $commits->render() !!}
</div>
@stop

@section('js')
<script id="commit-template" type="text/x-lodash-template">
    <div id="js-commit-<%= commit.shorthandId %>" class="row <% if (commit.status) { %> bg-success <% } else if (commit.status === 2) { %> bg-danger <% } else { %> bg-active <% } %>">
        <div class="col-sm-6">
            <strong><%= commit.message %></strong>
            <br>
            <small class="js-time-ago"><%= commit.timeAgo %></small>
        </div>
        <div class="col-sm-1">
            <small class="js-excecuted-time"><%= commit.excecutedTime %></small>
        </div>
        <div class="col-sm-1">
            <p class="js-status" style="<% if (commit.status === 1) { %> color:green; <% } else if (commit.status === 2) { %> color:red; <% } else { %> color:grey; <% } %>">
                <strong><%= commit.summary %></strong>
            </p>
        </div>
        <div class="col-sm-4 repo-buttons">
            <a class="badge-id" href="https://github.com/<%= commit.repo_name %>/commit/<%= commit.id %>">
                <%= commit.shorthandId %>
            </a>
            <a class="btn btn-sm btn-default" href="<%= commit.link %>">Show Details</a>
        </div>
    </div>
</script>

<script type="text/javascript">
    $(function() {
        StyleCI.Repo.RealTimeStatus();
    });
</script>
@stop
