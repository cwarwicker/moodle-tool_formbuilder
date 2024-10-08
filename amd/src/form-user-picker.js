define(['core/ajax', 'jquery'], function(ajax, $) {

    return {
        transport: function(selector, query, success, failure) {

            let promises = null;
            if (typeof query === "undefined") {
                query = '';
            }

            let calls = [{
                methodname: 'tool_formbuilder_search_users', args: {
                    search: query
                }
            }];

            promises = ajax.call(calls);
            $.when.apply($.when, promises).done(function(data) {
                success(data);
            }).fail(failure);

        },

        processResults: function(selector, data) {
            let results = [];
            for (let i = 0; i < data.users.length; i++) {

                let label = data.users[i].fullname;
                if (data.users[i].idnumber.length) {
                    label += ' (' + data.users[i].idnumber + ')';
                }

                results.push({
                    value: data.users[i].id,
                    label: label
                });

            }
            return results;
        },

    };

});