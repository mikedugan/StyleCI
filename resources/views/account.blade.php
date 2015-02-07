@extends(Config::get('core.default'))

@section('title')
Your Account
@stop

@section('top')
<div class="page-heading">
    <div class="container">
        <h1>Your Account</h1>
        <p>Here you can manage your StyleCI account.</p>
    </div>
</div>
@stop

@section('content')
<div class="modal fade" id="delete_account" tabindex="-1" role="dialog" aria-labelledby="delete_account" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete Account</h4>
            </div>
            <div class="modal-body">
                <p>You are about to delete your account and your repos from StyleCI. This process cannot be reverted, however you may still sign up again in the future should you change your mind.</p>
                <p>Are you sure you wish to continue?</p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-success" href="{{ route('account_delete_path') }}">Yes</a>
                <button class="btn btn-danger" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div role="tabpanel">
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li role="presentation" class="active">
            <a href="#repositories" aria-controls="repositories" role="tab" data-toggle="tab">Repositories</a>
        </li>
        <li role="presentation">
            <a href="#notifications" aria-controls="notifications" role="tab" data-toggle="tab">Notifications</a>
        </li>
        <li role="presentation">
            <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="repositories">
            <h2>Repositories</h2>
            <p>We're only showing your public repositories below</p>
            <hr>
            <div class="loading text-center hidden">
                <h3><i class="fa fa-circle-o-notch fa-spin"></i> Fetching your repositories...</h3>
            </div>
            <div class="repos">
                @if (isset($repos))
                    @forelse($repos as $id => $repo)
                    <div class="row">
                        <div class="col-sm-8">
                            <h4>{{ $repo['name'] }}</h4>
                            @if($repo['enabled'])
                            <h5>StyleCI is currently enabled on this repo.</h5>
                            @else
                            <h5>StyleCI is currently disabled on this repo.</h5>
                            @endif
                        </div>
                        <div class="col-sm-4 list-vcenter">
                            <div class="repo-controls">
                                @if($repo['enabled'])
                                <a class="btn btn-primary" href="{{ route('repo_path', $id) }}">
                                    <i class="fa fa-history"></i> Show Commits
                                </a>
                                <a class="btn btn-danger" href="{{ route('disable_repo_path', $id) }}" data-id="{{ $id }}" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Disabling...">
                                    <i class="fa fa-times"></i> Disable StyleCI
                                </a>
                                @else
                                <a class="btn btn-success" href="{{ route('enable_repo_path', $id) }}" data-id="{{ $id }}" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Enabling...">
                                    <i class="fa fa-check"></i> Enable StyleCI
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr>
                    @empty
                    <p class="lead">You have no public repositories we can access.</p>
                    @endforelse
                @endif
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="notifications">
            <h2>Notifications</h2>
            <p class="lead">TODO</p>
        </div>
        <div role="tabpanel" class="tab-pane" id="profile">
            <h2>Profile</h2>
            <div class="row">
                <div class="col-md-3">
                    <img src="{{ $currentUser->gravatar }}" alt="{{ $currentUser->name }}" />
                </div>
                <div class="col-md-9">
                    <dl class="profile">
                        <dt>GitHub</dt>
                        <dd>{{ $currentUser->username }}</dd>
                        <dt>Email</dt>
                        <dd>{{ $currentUser->email }}</dd>
                    </dl>
                </div>
            </div>
            <hr>
            <h2>Delete Account</h2>
            <p class="lead">You may delete your account here.</p>
            <p>Note that account deletion will remove all your data from our servers, so if you create a new account in the future, all your current analyses will be missing.</p>
            <br>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_account"><i class="fa fa-times"></i> Delete Account</button>
        </div>
    </div>
</div>
@stop

@section('bottom')
<script id="repos-template" type="text/x-lodash-template">
    <% if (_.size(repos) > 0) { %>
        <% _.forEach(repos, function(repo) { %>
        <div class="row">
            <div class="col-sm-8">
                <h4><%= repo.name %></h4>
                <% if (repo.enabled) { %>
                <h5>StyleCI is currently enabled on this repo.</h5>
                <% } else { %>
                <h5>StyleCI is currently disabled on this repo.</h5>
                <% } %>
            </div>
            <div class="col-sm-4 list-vcenter">
                <div class="repo-controls">
                    <% if (repo.enabled) { %>
                    <a class="btn btn-primary" href="{{ route('repo_path', '') }}/<%= repo.id %>">
                        <i class="fa fa-history"></i> Show Commits
                    </a>
                    <a class="btn btn-danger js-disable-repo" href="{{ route('disable_repo_path', '') }}/<%= repo.id %>" data-id="<%= repo.id %>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Disabling...">
                        <i class="fa fa-times"></i> Disable StyleCI
                    </a>
                    <% } else { %>
                    <a class="btn btn-success js-enable-repo" href="{{ route('enable_repo_path', '') }}/<%= repo.id %>" data-id="<%= repo.id %>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Enabling...">
                        <i class="fa fa-check"></i> Enable StyleCI
                    </a>
                    <% } %>
                </div>
            </div>
        </div>
        <hr>
        <% }); %>
    <% } else { %>
        <p class="lead">You have no public repositories we can access.</p>
    <% } %>
</script>

<script id="enabled-repo-template" type="text/x-lodash-template">
    <a class="btn btn-primary" href="{{ route('repo_path', '') }}/<%= repo.id %>">
        <i class="fa fa-history"></i> Show Commits
    </a>
    <a class="btn btn-danger js-disable-repo" href="{{ route('disable_repo_path', '') }}/<%= repo.id %>" data-id="<%= repo.id %>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Disabling...">
        <i class="fa fa-times"></i> Disable StyleCI
    </a>
</script>

<script id="disabled-repo-template" type="text/x-lodash-template">
    <a class="btn btn-success js-enable-repo" href="{{ route('enable_repo_path', '') }}/<%= repo.id %>" data-id="<%= repo.id %>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Enabling...">
        <i class="fa fa-check"></i> Enable StyleCI
    </a>
</script>

@if (!isset($repos))
<script charset="utf-8">
    $(function() {
        StyleCI.Account.getRepos('{{ route('account_repos_path') }}');
    });
</script>
@endif
@stop
