
/*====================  Typeahead  Searching ================================ */
var skillSearch = [];
function initTypeaheadSearch() {
    
    skillSearch = new Bloodhound({
        datumTokenizer: function (datum) {
            return Bloodhound.tokenizers.whitespace(datum.title);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: scripts_vars.ajaxurl + '?action=wtTypeaheadSearchSkills&fn=wtTypeaheadSearchSkills',
            ttl: 86400000,
            filter: function (data) {
                return jQuery.map(data, function (temp, key) {
                    return jQuery.map(temp, function (detail) {
                        return {
                            title: detail.title,
                            term_id: detail.term_id,
                            slug: detail.slug
                        };
                    });
                });
            }
        },
        remote: {
            url: scripts_vars.ajaxurl + '?action=wtTypeaheadSearchSkills&fn=wtTypeaheadSearchSkills&terms=%QUERY',
            wildcard: '%QUERY',
            //ttl: 86400000,
            filter: function (data) {
                return jQuery.map(data, function (temp, key) {
                    return jQuery.map(temp.items, function (detail) {
                        return {
                            title: detail.title,
                            term_id: detail.term_id,
                            slug: detail.slug
                        };
                    });
                });
            }
        }
    });

    let skills_data = {
        name: 'skillSearch',
        source: skillSearchWithDefaults,
        displayKey: 'title',
        limit: 200,
        templates: {
            suggestion: function (response) {
                var query_param = response._query;
                var load_skills = wp.template('load-skills');
                var data = { skills: response, query: query_param };
                load_skills = load_skills(data);
                var search_result = load_skills;
                return search_result;
            } 
        }
    };

    let setting_data = {
        hint: true,
        highlight: true,
        minLength: 0,
        showHintOnFocus: true,
        classNames: {
            input: 'Typeahead-input',
            hint: 'Typeahead-hint',
            selectable: 'Typeahead-selectable',
            menu: 'wt-filterscroll wt-dropdown-listing'
        }
    };

    function typeahead_asyncrequest() {
        let _this = jQuery(this);
        _this.parents('.lx-search-typeahead').addClass('lx-typeahead_loader');
    }

    function typeahead_asyncreceive() {
        let _this = jQuery(this);
        _this.parents('.lx-search-typeahead').removeClass("lx-typeahead_loader");
    }

    jQuery('.wt-typeahead-skills-search').typeahead(
        setting_data,
        skills_data

    ).on('typeahead:asyncrequest', typeahead_asyncrequest
    ).on('typeahead:asynccancel typeahead:asyncreceive', typeahead_asyncreceive);

    jQuery('.wt-typeahead-skills-search').on('typeahead:selected', function(obj, datum, name) {
        let list_item = '<li><span data-term_id="'+datum.term_id+'" data-name="'+datum.slug+'" >'+datum.title+'<a href="javascript:void(0);" class="wt-term-remove-options" data-term_id="'+datum.term_id+'" data-name="'+datum.title+'"><em class="fa fa-close"></em></a></span><input type="hidden" name="skills[]"  value="'+datum.slug+'" ></li>'; 
        let is_valid = true; 
        jQuery(".wt-term-remove-options").each(function (index, item) {
            let _this = jQuery(this);
            let term_id = _this.data('term_id');
            if(term_id == datum.term_id){
               is_valid = false;
            }
        });
        if(is_valid){
            jQuery('.wt-selected-skills').removeClass('d-none');
            jQuery('.wt-selected-skills .wt-skills-selection').append(list_item);
        }
        jQuery('.wt-typeahead-skills-search').typeahead('val','');
        jQuery('.wt-typeahead-skills-search').blur();
    });

    jQuery('.wt-filterscroll').mCustomScrollbar({
        axis:"y",
        mouseWheel: true,
        advanced: {
            updateOnContentResize: true
        }
    });

}

function skillSearchWithDefaults(q, sync) {
    if (q === '') {
      sync(skillSearch.all()); 
    }
    else {
        skillSearch.search(q, sync);
    }
}
document.addEventListener("readystatechange", function (ev) {
    if (document.readyState == 'complete') {
        setTimeout(function () {
            initTypeaheadSearch();
        }, 1000);
    }
});
/*==================== End Typeahead  Searching ================================ */
