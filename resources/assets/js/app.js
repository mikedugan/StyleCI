$(function() {
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
                window.location.href = '/';
            },
            403: function () {
                window.location.href = '/';
            }
        }
    });

    // App setup
    window.StyleCI = {};

    StyleCI.Account = {
        getRepos: function(url) {
            var $tpl = $('#repos-template'),
                $reposHolder = $('.repos'),
                $loading = $('.loading');

            $loading = $('.loading').removeClass('hidden');

            $.get(url)
                .done(function(response) {
                    var reposTpl = _.template($tpl.html());
                    var sortedData = _.sortBy(response.data, function(repo, key) {
                        repo.id = key;
                        return repo.name.toLowerCase();
                    });
                    $reposHolder.html(reposTpl({repos: sortedData}));
                })
                .fail(function(response) {
                    alert(response.responseJSON.msg);
                })
                .always(function() {
                    $loading.hide();
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
                    alert(response.responseJSON.msg);
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
});
