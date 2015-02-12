$(function() {
    // App setup
    window.StyleCI = {};

    // Global Ajax Setup
    $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
        var token;
        if (! options.crossDomain) {
            token = $('meta[name="token"]').attr('content');
            if (token) {
                return jqXHR.setRequestHeader('X-CSRF-Token', token);
            }
        }
    });

    $.ajaxSetup({
        statusCode: {
            401: function () {
                (new StyleCI.Notifier()).notify('Your session has expired, please login.');
            },
            403: function () {
                (new StyleCI.Notifier()).notify('Your session has expired, please login.');
            }
        }
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    StyleCI.globals = {
        host: window.location.host,
        base_url: window.location.protocol + "//" + window.location.host,
        url: document.URL
    };

    StyleCI.Events = {};
    StyleCI.Listeners = {};

    StyleCI.Notifier = function () {
        this.notify = function (message, type, options) {
            type = (typeof type === 'undefined' || type === 'error') ? 'danger' : type;
            var $alertsHolder = $('.alerts');

            var defaultOptions = {
                dismiss: false,
            };

            options = _.extend(defaultOptions, options);

            var alertTpl = _.template('<div class="alert alert-<%= type %> styleci-alert"><div class="container"><a class="close" data-dismiss="alert">×</a><%= message %></div></div>');
            $alertsHolder.html(alertTpl({message: message, type: type}));

            $('html, body').animate({
                scrollTop: $('body').offset().top
            }, 500);
        };
    };

    StyleCI.Events.RealTime = (function () {
        var instance;

        function createInstance() {
            return new Pusher($('meta[name="pusher"]').attr('content'));
        }

        return {
            getInstance: function () {
                if (! instance) {
                    instance = createInstance();
                }
                return instance;
            },
            getChannel: function(ch) {
                return this.getInstance().subscribe("ch-" + ch);
            }
        };
    })();

    StyleCI.Listeners.Repo = {
        CommitsStatusChangeEventHandler: function(data) {
            var $commit = $("#js-commit-" + data.event.shorthandId);

            if ($commit.length) {
                var $status = $commit.find('p.js-status'),
                    $timeAgo = $commit.find('.js-time-ago'),
                    $time = $commit.find('.js-excecuted-time');

                $status.html("<strong>" + data.event.summary + "</strong>");
                $timeAgo.html(data.event.timeAgo);
                $time.html(data.event.excecutedTime);

                $commit.removeClass("bg-success")
                    .removeClass("bg-danger")
                    .removeClass("bg-active");

                if (data.event.status === 1) {
                    $status.css("color", "green");
                    $commit.addClass("bg-success");
                } else if (data.event.status === 2) {
                    $status.css("color", "red");
                    $commit.addClass("bg-danger");
                } else {
                    $status.css("color", "grey");
                    $commit.addClass("bg-active");
                }
            } else {
                var $tpl = $('#commit-template'),
                    $commitsHolder = $('.commits');

                var commitsTpl = _.template($tpl.html());
                $commitsHolder.prepend(commitsTpl({commit: data.event}));
            }
        },
    };

    StyleCI.Listeners.Commit = {
        CommitStatusChangeEventHandler: function(data) {
            var $status = $('p.js-status'),
                $time = $('.js-time-ago');

            $status.html(data.event.description);
            $time.html(data.event.timeAgo);

            if (data.event.status === 1) {
                $status.css("color", "green");
            } else if (data.event.status === 2) {
                $status.css("color", "red");
            } else {
                $status.css("color", "grey");
            }
        }
    };

    StyleCI.Repo = {
        RealTimeStatus: function() {
            StyleCI.Events.RealTime.getChannel($(".js-channel").data('channel')).bind(
                "AnalysisHasCompletedEvent",
                StyleCI.Listeners.Repo.CommitsStatusChangeEventHandler
            );
        }
    };

    StyleCI.Commit = {
        RealTimeStatus: function() {
            StyleCI.Events.RealTime.getChannel($(".js-channel").data('channel')).bind(
                "AnalysisHasCompletedEvent",
                StyleCI.Listeners.Commit.CommitStatusChangeEventHandler
            );
        }
    };

    StyleCI.Account = {
        getRepos: function(url) {
            var $tpl = $('#repos-template'),
                $reposHolder = $('.repos'),
                $loading = $('.loading');

            $loading.show();
            $reposHolder.hide();

            var requestUrl = (typeof url !== 'undefined') ? url : StyleCI.globals.base_url + '/account/repos';

            return $.get(requestUrl)
                .done(function(response) {
                    var reposTpl = _.template($tpl.html());
                    var sortedData = _.sortBy(response.data, function(repo, key) {
                        repo.id = key;
                        return repo.name.toLowerCase();
                    });
                    $reposHolder.html(reposTpl({repos: sortedData}));
                    $reposHolder.show();
                })
                .fail(function(response) {
                    (new StyleCI.Notifier()).notify(response.responseJSON.msg);
                })
                .always(function() {
                    $loading.hide();
                });
        },
        syncRepos: function(btn) {
            var self = this,
                $reposHolder = $('.repos'),
                $loading = $('.loading');

            btn.button('loading');

            $loading.show();
            $reposHolder.hide();

            return $.get(btn.attr('href'))
                .done(function(response) {
                    $.when(self.getRepos()).then(function() {
                        btn.button('reset').blur();
                    });
                })
                .fail(function(response) {
                    (new StyleCI.Notifier()).notify(response.responseJSON.msg);
                });
        },
        enableOrDisableRepo: function(btn) {
            var repoId = btn.data('id'),
                $enabledTpl = $('#enabled-repo-template'),
                $disabledTpl = $('#disabled-repo-template'),
                $controlsHolder = btn.closest('.repo-controls');

            btn.button('loading');

            $.get(btn.attr('href'))
                .done(function(response) {
                    if (response.enabled) {
                        var enabledTpl = _.template($enabledTpl.html());
                        $controlsHolder.html(enabledTpl({repo: {id: repoId}}));
                    } else {
                        var disabledTpl = _.template($disabledTpl.html());
                        $controlsHolder.html(disabledTpl({repo: {id: repoId}}));
                    }
                })
                .fail(function(response) {
                    (new StyleCI.Notifier()).notify(response.responseJSON.msg);
                })
                .always(function() {
                    btn.button('reset');
                });
        }
    };

    $(document.body).on('click', '.js-enable-repo, .js-disable-repo', function(e) {
        e.preventDefault();
        StyleCI.Account.enableOrDisableRepo($(this));
        return false;
    });

    $('.js-sync-repos').on('click', function(e) {
        e.preventDefault();
        StyleCI.Account.syncRepos($(this));
        return false;
    });
});
