// AutoComplete implementation for the yuilibrary.com main search box.
YUI.add('search', function (Y) {

var Lang   = Y.Lang,
    Node   = Y.Node,
    YArray = Y.Array,
    template;

var allevoSearch = Y.one('#main-search .search-input').plug(Y.Plugin.AutoComplete, {
    maxResults       : 20,
    scrollIntoView   : true,
    activateFirstItem: true,

    resultHighlighter: 'phraseMatch',
    resultFormatter  : formatResults,
    resultListLocator: 'data.results',
    resultTextLocator: locateResultText,

    source: '/request/search.php?q={query}&count={maxResults}&action=publicsearch',

    on: {
        select: onSelect,
		query: onQuery,
		results: onResults
    }
});

// -- Result Template --------------------------------------------------
template =
    '<div class="result {resultType}">' +
        '<a href="{url}">' +
            '<h3 class="title">{name}</h3>' +
            '<span class="type">{resultType}</span>' +
            '<div class="description">{description}</div>' +
            '<span class="className">{class}</span>' +
        '</a>' +
    '</div>';

// -- Private Functions ------------------------------------------------
function formatResults(query, results) {
    return YArray.map(results, function (result) {
													  
			Y.log(result);										  
        var raw  = Y.merge(result.raw), // create a copy
            desc = raw.description || '';

        // Convert description to text and truncate it if necessary.
        desc = Node.create('<div>' + desc + '</div>').get('text');

        if (desc.length > 65) {
            desc = Y.Escape.html(desc.substr(0, 65)) + ' &hellip;';
        } else {
            desc = Y.Escape.html(desc);
        }

        raw['class'] || (raw['class'] = '');
        raw.description = desc;

        // Use the highlighted result name.
        raw.name = result.highlighted;

        return Lang.sub(template, raw);
    });
}

function locateResultText(result) {



    return result.displayName || result.name;
}

// -- Event Handlers ---------------------------------------------------
function onSelect(e) {
    var button = e.originEvent ? e.originEvent.button : null;

    e.preventDefault();

    // Don't navigate in the current window if any button other than the
    // left button was clicked to select this result. For some reason
    // e.button is mapped to e.which for keyboard events, thus the need
    // for the > 3 check.
    if (button === 1 || button > 3) {
        Y.config.win.location = e.result.raw.url;
    }

    this.hide();
}


function onQuery(e) {
	allevoSearch.addClass('waiting');
	Y.log('fire up search');
}

function onResults(e) {
	allevoSearch.removeClass('waiting');
}










}, '1.0.0', {requires: [
    'autocomplete', 'autocomplete-highlighters', 'node-pluginhost'
]});